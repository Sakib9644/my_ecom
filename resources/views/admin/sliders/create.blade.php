@extends('layouts.admin')

@section('page_title', 'Add Slider Image')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">
        <div class="p-4 sm:p-6 lg:p-8">
            <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Image Upload -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Slider Image <span class="text-red-500">*</span></label>
                    <p class="text-xs text-slate-400 mb-3">Recommended: 1920×600px, max 5MB. JPEG, PNG, WebP.</p>
                    <div class="relative">
                        <input type="file" name="image" id="image" accept="image/*" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('image') border-red-400 @enderror">
                    </div>
                    @error('image')
                        <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                    <!-- Preview -->
                    <div id="preview" class="mt-3 hidden">
                        <p class="text-xs font-semibold text-slate-500 mb-2">Preview:</p>
                        <img id="preview-img" class="rounded-2xl border border-slate-200 max-h-48 w-full object-cover">
                    </div>
                </div>

                <!-- Link -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Link URL <span class="text-slate-400 font-normal">(optional)</span></label>
                    <input type="url" name="link" value="{{ old('link') }}" placeholder="https://example.com/products/..." 
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('link') border-red-400 @enderror">
                    <p class="text-xs text-slate-400 mt-1.5">Where users will go when clicking the slide. Leave empty for no link.</p>
                    @error('link')
                        <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sort Order -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Sort Order <span class="text-slate-400 font-normal">(optional)</span></label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                        class="w-32 px-4 py-3 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('sort_order') border-red-400 @enderror">
                    <p class="text-xs text-slate-400 mt-1.5">Lower numbers appear first.</p>
                    @error('sort_order')
                        <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Toggle -->
                <div class="mb-8">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked
                            class="h-5 w-5 rounded-md border-slate-300 text-primary-600 focus:ring-primary-500">
                        <div>
                            <span class="text-sm font-bold text-slate-700">Active</span>
                            <p class="text-xs text-slate-400">Show this slide on the homepage</p>
                        </div>
                    </label>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 pt-6 border-t border-slate-200">
                    <button type="submit" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-sm font-bold transition-all shadow-md shadow-primary-500/20">
                        <i class="fa-solid fa-upload mr-1.5"></i> Upload Slide
                    </button>
                    <a href="{{ route('admin.sliders.index') }}" class="px-6 py-2.5 border border-slate-300 hover:bg-slate-50 text-slate-600 rounded-xl text-sm font-bold transition-all">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('image').addEventListener('change', function(e) {
    const preview = document.getElementById('preview');
    const img = document.getElementById('preview-img');
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(ev) {
            img.src = ev.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
});
</script>
@endsection
