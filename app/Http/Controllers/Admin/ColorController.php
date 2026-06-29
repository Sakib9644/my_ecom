<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ColorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $colors = Color::orderBy('sort_order', 'asc')->orderBy('name', 'asc');
            return DataTables::of($colors)
                ->addIndexColumn()
                ->addColumn('swatch', fn(Color $c) => $c->hex_code
                    ? '<span class="inline-block h-6 w-6 rounded-full border border-slate-200 ring-1 ring-black/5" style="background-color: '.$c->hex_code.'"></span>'
                    : '<span class="text-slate-400">—</span>')
                ->addColumn('action', fn(Color $c) => '
                    <div class="flex items-center justify-end gap-2">
                        <a href="'.route('admin.colors.edit', $c->id).'" class="p-2 border border-slate-300 hover:bg-slate-50 text-slate-600 rounded-xl transition-colors">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </a>
                        <form action="'.route('admin.colors.destroy', $c->id).'" method="POST" onsubmit="return confirm(\'Are you sure?\')" style="display:inline">
                            '.csrf_field().method_field('DELETE').'
                            <button type="submit" class="p-2 border border-red-200 hover:bg-red-50 text-red-500 rounded-xl transition-colors">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </form>
                    </div>')
                ->rawColumns(['swatch', 'action'])
                ->make(true);
        }
        return view('admin.colors.index');
    }

    public function create()
    {
        return view('admin.colors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:colors,name',
            'hex_code' => 'nullable|string|max:7|regex:/^#[a-fA-F0-9]{6}$/',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        Color::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'hex_code' => $request->hex_code,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.colors.index')->with('success', 'Color created successfully.');
    }

    public function edit(Color $color)
    {
        return view('admin.colors.edit', compact('color'));
    }

    public function update(Request $request, Color $color)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:colors,name,' . $color->id,
            'hex_code' => 'nullable|string|max:7|regex:/^#[a-fA-F0-9]{6}$/',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $color->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'hex_code' => $request->hex_code,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.colors.index')->with('success', 'Color updated successfully.');
    }

    public function destroy(Color $color)
    {
        $color->delete();
        return redirect()->route('admin.colors.index')->with('success', 'Color deleted successfully.');
    }
}
