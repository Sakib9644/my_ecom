<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $products = Product::with('category')->latest();
            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('image', fn(Product $p) => '<div class="h-10 w-10 rounded-lg overflow-hidden bg-slate-100 border border-slate-200 shrink-0"><img src="'.$p->image_url.'" alt="'.$p->name.'" class="h-full w-full object-cover"></div>')
                ->editColumn('name', fn(Product $p) => '<div class="flex items-center gap-3">'.$p->name.'</div>')
                ->addColumn('category_name', fn(Product $p) => $p->category?->name ?? 'Uncategorized')
                ->editColumn('price', fn(Product $p) => (float) $p->price)
                ->addColumn('stock_badge', function(Product $p) {
                    if (!empty($p->sizes) || !empty($p->colors)) {
                        $label = '<span class="px-2 py-0.5 rounded-md text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100 mb-1 inline-block">Variant Stock ('.$p->total_stock.')</span>';
                        if (!empty($p->variant_stock)) {
                            $label .= '<div class="text-[10px] text-slate-500 mt-1 space-y-0.5 leading-tight">';
                            foreach ($p->variant_stock as $key => $val) {
                                if (is_array($val)) {
                                    foreach ($val as $subKey => $subVal) {
                                        $label .= "<div>{$key} / {$subKey}: <b>{$subVal}</b></div>";
                                    }
                                } else {
                                    $label .= "<div>{$key}: <b>{$val}</b></div>";
                                }
                            }
                            $label .= '</div>';
                        }
                        return $label;
                    }
                    
                    $totalStock = $p->stock;
                    if ($totalStock <= 0) {
                        return '<span class="px-2 py-0.5 rounded-md text-xs font-bold bg-red-50 text-red-700 border border-red-100">Out of stock</span>';
                    }
                    return $totalStock <= 5
                        ? '<span class="px-2 py-0.5 rounded-md text-xs font-bold bg-orange-50 text-orange-700 border border-orange-100">Low Stock ('.$totalStock.')</span>'
                        : '<span class="font-bold text-slate-700">'.$totalStock.' items</span>';
                })
                ->addColumn('featured_badge', fn(Product $p) => $p->is_featured
                    ? '<button onclick="toggleFeatured('.$p->id.', this)" class="px-2 py-0.5 rounded-md text-xs font-bold bg-primary-50 text-primary-700 border border-primary-100 hover:bg-primary-100 transition-colors cursor-pointer"><i class="fa-solid fa-star text-[10px] mr-1"></i>Yes</button>'
                    : '<button onclick="toggleFeatured('.$p->id.', this)" class="text-slate-400 font-semibold hover:text-primary-600 transition-colors cursor-pointer">-</button>')
                ->addColumn('action', fn(Product $p) => '
                    <div class="flex items-center justify-end gap-2">
                        <a href="'.route('admin.products.edit', $p->id).'" class="p-2 border border-slate-300 hover:bg-slate-50 text-slate-600 rounded-xl transition-colors">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </a>
                        <form action="'.route('admin.products.destroy', $p->id).'" method="POST" onsubmit="return confirm(\'Are you sure?\')" style="display:inline">
                            '.csrf_field().method_field('DELETE').'
                            <button type="submit" class="p-2 border border-red-200 hover:bg-red-50 text-red-500 rounded-xl transition-colors">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </form>
                    </div>')
                ->rawColumns(['image', 'name', 'stock_badge', 'featured_badge', 'action'])
                ->make(true);
        }
        return view('admin.products.index');
    }

    public function create()
    {
        $categories = Category::all();
        $sizes = Size::orderBy('sort_order', 'asc')->orderBy('name', 'asc')->get();
        $colors = Color::orderBy('sort_order', 'asc')->orderBy('name', 'asc')->get();
        return view('admin.products.create', compact('categories', 'sizes', 'colors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'nullable|boolean',
            'sizes' => 'nullable|array',
            'sizes.*' => 'exists:sizes,id',
            'colors' => 'nullable|array',
            'colors.*' => 'exists:colors,id',
            'color_images' => 'nullable|array',
            'color_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'variant_stock' => 'nullable|array',
        ]);

        $slug = Str::slug($request->name);
        if (Product::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . time();
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Resolve size/color IDs to names for JSON storage
        $sizeNames = !empty($request->sizes)
            ? Size::whereIn('id', $request->sizes)->orderBy('sort_order', 'asc')->pluck('name')->toArray()
            : null;
        $colorNames = !empty($request->colors)
            ? Color::whereIn('id', $request->colors)->orderBy('sort_order', 'asc')->pluck('name')->toArray()
            : null;

        // Build variant_stock from form input
        $variantStock = $this->buildVariantStock($request, $sizeNames, $colorNames);

        // Upload color images if provided
        $colorImageMap = [];
        if ($request->hasFile('color_images') && !empty($colorNames)) {
            foreach ($request->file('color_images') as $colorId => $file) {
                $color = Color::find($colorId);
                if ($color && $file) {
                    $colorImageMap[$color->name] = $file->store('products/colors', 'public');
                }
            }
        }

        Product::create([
            'category_id'   => $request->category_id,
            'name'          => $request->name,
            'slug'          => $slug,
            'description'   => $request->description,
            'price'         => $request->price,
            'stock'         => $variantStock ? 0 : $request->stock,
            'image'         => $imagePath,
            'is_featured'   => $request->has('is_featured'),
            'sizes'         => $sizeNames,
            'colors'        => $colorNames,
            'color_images'  => !empty($colorImageMap) ? $colorImageMap : null,
            'variant_stock' => $variantStock,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $sizes = Size::orderBy('sort_order', 'asc')->orderBy('name', 'asc')->get();
        $colors = Color::orderBy('sort_order', 'asc')->orderBy('name', 'asc')->get();
        return view('admin.products.edit', compact('product', 'categories', 'sizes', 'colors'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'nullable|boolean',
            'sizes' => 'nullable|array',
            'sizes.*' => 'exists:sizes,id',
            'colors' => 'nullable|array',
            'colors.*' => 'exists:colors,id',
            'color_images' => 'nullable|array',
            'color_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'variant_stock' => 'nullable|array',
        ]);

        $slug = Str::slug($request->name);
        if (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
            $slug = $slug . '-' . time();
        }

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Resolve size/color IDs to names for JSON storage
        $sizeNames = !empty($request->sizes)
            ? Size::whereIn('id', $request->sizes)->orderBy('sort_order', 'asc')->pluck('name')->toArray()
            : null;
        $colorNames = !empty($request->colors)
            ? Color::whereIn('id', $request->colors)->orderBy('sort_order', 'asc')->pluck('name')->toArray()
            : null;

        // Build variant_stock from form input
        $variantStock = $this->buildVariantStock($request, $sizeNames, $colorNames);

        // Build new color images map: keep existing ones and overwrite with newly uploaded files
        $colorImageMap = $product->color_images ?? [];

        // Delete images for colors that have been deselected
        if (!empty($colorImageMap) && !empty($colorNames)) {
            foreach ($colorImageMap as $colorName => $path) {
                if (!in_array($colorName, $colorNames)) {
                    if ($path && Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                    unset($colorImageMap[$colorName]);
                }
            }
        }

        // Upload new color images
        if ($request->hasFile('color_images')) {
            foreach ($request->file('color_images') as $colorId => $file) {
                $color = Color::find($colorId);
                if ($color && $file) {
                    // Delete old image for this color if it exists
                    if (isset($colorImageMap[$color->name])) {
                        $oldPath = $colorImageMap[$color->name];
                        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                            Storage::disk('public')->delete($oldPath);
                        }
                    }
                    $colorImageMap[$color->name] = $file->store('products/colors', 'public');
                }
            }
        }

        $product->update([
            'category_id'   => $request->category_id,
            'name'          => $request->name,
            'slug'          => $slug,
            'description'   => $request->description,
            'price'         => $request->price,
            'stock'         => $variantStock ? 0 : $request->stock,
            'image'         => $imagePath,
            'is_featured'   => $request->has('is_featured'),
            'sizes'         => $sizeNames,
            'colors'        => $colorNames,
            'color_images'  => !empty($colorImageMap) ? $colorImageMap : null,
            'variant_stock' => $variantStock,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Build variant_stock array from form inputs.
     * If both colors+sizes: ["White" => ["30" => 5, "35" => 3]]
     * If only colors: ["White" => 10]
     * If only sizes: ["30" => 10]
     */
    private function buildVariantStock(Request $request, ?array $sizeNames, ?array $colorNames): ?array
    {
        $hasColors = !empty($colorNames);
        $hasSizes  = !empty($sizeNames);

        if (!$hasColors && !$hasSizes) {
            return null;
        }

        $variantStock = [];
        $input = $request->input('variant_stock', []);

        if ($hasColors && $hasSizes) {
            // Grid: color -> size -> stock
            foreach ($colorNames as $colorName) {
                foreach ($sizeNames as $sizeName) {
                    $key = $colorName . '|' . $sizeName;
                    $variantStock[$colorName][$sizeName] = max(0, (int)($input[$key] ?? 0));
                }
            }
        } elseif ($hasColors) {
            foreach ($colorNames as $colorName) {
                $variantStock[$colorName] = max(0, (int)($input[$colorName] ?? 0));
            }
        } elseif ($hasSizes) {
            foreach ($sizeNames as $sizeName) {
                $variantStock[$sizeName] = max(0, (int)($input[$sizeName] ?? 0));
            }
        }

        return $variantStock;
    }

    public function toggleFeatured(Product $product)
    {
        $product->update(['is_featured' => !$product->is_featured]);

        return response()->json([
            'success' => true,
            'is_featured' => $product->is_featured,
        ]);
    }

    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // Delete all color images
        $product->deleteColorImages();

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
