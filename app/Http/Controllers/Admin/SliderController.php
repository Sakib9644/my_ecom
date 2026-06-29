<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class SliderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $sliders = Slider::orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
            return DataTables::of($sliders)
                ->addIndexColumn()
                ->addColumn('image_preview', fn(Slider $s) =>
                    '<div class="h-14 w-24 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                        <img src="'.$s->image_url.'" alt="Slide" class="h-full w-full object-cover">
                    </div>')
                ->editColumn('is_active', fn(Slider $s) => $s->is_active
                    ? '<span class="px-2 py-0.5 rounded-md text-xs font-bold bg-green-50 text-green-700 border border-green-100"><i class="fa-solid fa-check text-[10px] mr-1"></i>Active</span>'
                    : '<span class="px-2 py-0.5 rounded-md text-xs font-bold bg-slate-50 text-slate-500 border border-slate-200">Inactive</span>')
                ->editColumn('sort_order', fn(Slider $s) =>
                    '<span class="font-mono text-sm font-bold text-slate-600">'.$s->sort_order.'</span>')
                ->addColumn('action', fn(Slider $s) => '
                    <div class="flex items-center justify-end gap-2">
                        <a href="'.route('admin.sliders.edit', $s->id).'" class="p-2 border border-slate-300 hover:bg-slate-50 text-slate-600 rounded-xl transition-colors">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </a>
                        <form action="'.route('admin.sliders.destroy', $s->id).'" method="POST" onsubmit="return confirm(\'Are you sure?\')" style="display:inline">
                            '.csrf_field().method_field('DELETE').'
                            <button type="submit" class="p-2 border border-red-200 hover:bg-red-50 text-red-500 rounded-xl transition-colors">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </form>
                    </div>')
                ->rawColumns(['image_preview', 'is_active', 'sort_order', 'action'])
                ->make(true);
        }
        return view('admin.sliders.index');
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'link' => 'nullable|url|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $imagePath = $request->file('image')->store('sliders', 'public');

        Slider::create([
            'image' => $imagePath,
            'link' => $request->link,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider image uploaded successfully.');
    }

    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'link' => 'nullable|url|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data = [
            'link' => $request->link,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($slider->image && Storage::disk('public')->exists($slider->image)) {
                Storage::disk('public')->delete($slider->image);
            }
            $data['image'] = $request->file('image')->store('sliders', 'public');
        }

        $slider->update($data);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider updated successfully.');
    }

    public function destroy(Slider $slider)
    {
        if ($slider->image && Storage::disk('public')->exists($slider->image)) {
            Storage::disk('public')->delete($slider->image);
        }

        $slider->delete();

        return redirect()->route('admin.sliders.index')->with('success', 'Slider deleted successfully.');
    }
}
