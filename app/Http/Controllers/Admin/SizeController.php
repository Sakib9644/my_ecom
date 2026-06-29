<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class SizeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $sizes = Size::orderBy('sort_order', 'asc')->orderBy('name', 'asc');
            return DataTables::of($sizes)
                ->addIndexColumn()
                ->addColumn('category_badge', fn(Size $s) => $s->category
                    ? '<span class="px-2 py-0.5 rounded-md text-xs font-bold bg-slate-100 text-slate-600">'.$s->category.'</span>'
                    : '<span class="text-slate-400">—</span>')
                ->addColumn('action', fn(Size $s) => '
                    <div class="flex items-center justify-end gap-2">
                        <a href="'.route('admin.sizes.edit', $s->id).'" class="p-2 border border-slate-300 hover:bg-slate-50 text-slate-600 rounded-xl transition-colors">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </a>
                        <form action="'.route('admin.sizes.destroy', $s->id).'" method="POST" onsubmit="return confirm(\'Are you sure?\')" style="display:inline">
                            '.csrf_field().method_field('DELETE').'
                            <button type="submit" class="p-2 border border-red-200 hover:bg-red-50 text-red-500 rounded-xl transition-colors">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </form>
                    </div>')
                ->rawColumns(['category_badge', 'action'])
                ->make(true);
        }
        return view('admin.sizes.index');
    }

    public function create()
    {
        return view('admin.sizes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sizes,name',
            'category' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        Size::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'category' => $request->category,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.sizes.index')->with('success', 'Size created successfully.');
    }

    public function edit(Size $size)
    {
        return view('admin.sizes.edit', compact('size'));
    }

    public function update(Request $request, Size $size)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sizes,name,' . $size->id,
            'category' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $size->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'category' => $request->category,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.sizes.index')->with('success', 'Size updated successfully.');
    }

    public function destroy(Size $size)
    {
        $size->delete();
        return redirect()->route('admin.sizes.index')->with('success', 'Size deleted successfully.');
    }
}
