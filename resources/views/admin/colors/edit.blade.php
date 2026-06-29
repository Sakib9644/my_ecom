@extends('layouts.admin')

@section('page_title', 'Edit Color: ' . $color->name)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.colors.index') }}" class="text-sm font-semibold text-slate-500 hover:text-primary-600 flex items-center gap-1.5 w-fit">
        <i class="fa-solid fa-arrow-left text-xs"></i> Back to list
    </a>
</div>

<div class="max-w-xl bg-white border border-slate-200 rounded-3xl p-8 shadow-sm">
    <form action="{{ route('admin.colors.update', $color->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Name -->
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase">Color Name</label>
            <input type="text" name="name" value="{{ old('name', $color->name) }}" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Hex Code -->
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase">Hex Code <span class="text-slate-400 font-normal normal-case">(optional)</span></label>
            <div class="flex items-center gap-3">
                <input type="color" id="color-picker" value="{{ old('hex_code', $color->hex_code ?? '#0ea5e9') }}" class="h-10 w-16 rounded-xl border border-slate-300 cursor-pointer bg-white p-1">
                <input type="text" name="hex_code" id="hex-code-input" value="{{ old('hex_code', $color->hex_code) }}" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white" placeholder="#1e293b">
            </div>
            <p class="text-[10px] text-slate-400 mt-1">Pick a color or type the hex code manually. Used for visual swatches on the storefront.</p>
            @error('hex_code')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Sort Order -->
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase">Sort Order</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $color->sort_order) }}" min="0" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
            @error('sort_order')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit -->
        <div class="pt-4 border-t border-slate-100 flex gap-4 justify-end">
            <a href="{{ route('admin.colors.index') }}" class="px-5 py-2.5 border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold rounded-xl text-xs transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl text-xs transition-colors shadow-md shadow-primary-500/10">
                Update Color
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    const colorPicker = document.getElementById('color-picker');
    const hexInput = document.getElementById('hex-code-input');

    colorPicker.addEventListener('input', () => {
        hexInput.value = colorPicker.value;
    });

    hexInput.addEventListener('input', () => {
        const val = hexInput.value.trim();
        if (/^#[0-9a-fA-F]{6}$/.test(val)) {
            colorPicker.value = val;
        }
    });
</script>
@endsection
