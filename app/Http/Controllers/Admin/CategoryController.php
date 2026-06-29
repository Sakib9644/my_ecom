<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $categories = Category::withCount('products');
            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('action', fn(Category $c) => '
                    <div class="flex items-center justify-end gap-2">
                        <a href="'.route('admin.categories.edit', $c->id).'" class="p-2 border border-slate-300 hover:bg-slate-50 text-slate-600 rounded-xl transition-colors">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </a>
                        <form action="'.route('admin.categories.destroy', $c->id).'" method="POST" onsubmit="return confirm(\'Are you sure?\')" style="display:inline">
                            '.csrf_field().method_field('DELETE').'
                            <button type="submit" class="p-2 border border-red-200 hover:bg-red-50 text-red-500 rounded-xl transition-colors">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </form>
                    </div>')
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.categories.index');
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        // Category can be deleted; products associated will have category_id set to null
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
