@extends('layouts.admin')

@section('page_title', 'Site Settings')

@section('content')
<div class="max-w-2xl mx-auto space-y-8">
    <!-- Site Name -->
    <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">
        <div class="p-6 sm:p-8">
            <h2 class="text-lg font-bold text-slate-900 mb-1">Site Name</h2>
            <p class="text-sm text-slate-500 mb-6">Update the site name shown in the browser tab title and footer.</p>

            <form action="{{ route('admin.settings.update-name') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Site Name</label>
                    <input type="text" name="site_name" value="{{ old('site_name', $siteName) }}" required
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('site_name') border-red-400 @enderror">
                    @error('site_name')
                        <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 border-t border-slate-200">
                    <button type="submit" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-sm font-bold transition-all shadow-md shadow-primary-500/20">
                        <i class="fa-solid fa-save mr-1.5"></i> Update Site Name
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Favicon -->
    <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">
        <div class="p-6 sm:p-8">
            <h2 class="text-lg font-bold text-slate-900 mb-1">Favicon</h2>
            <p class="text-sm text-slate-500 mb-6">Upload a favicon for your site. Recommended: 32×32px, PNG or ICO format.</p>

            @php $favicon = \App\Models\Setting::get('favicon'); @endphp

            @if($favicon)
                <div class="mb-6 flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-200">
                    <div class="h-12 w-12 rounded-xl overflow-hidden bg-white border border-slate-200 flex items-center justify-center shrink-0 shadow-sm">
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($favicon) }}" alt="Current favicon" class="h-8 w-8 object-contain">
                    </div>
                    <div class="flex-grow">
                        <p class="text-sm font-semibold text-slate-700">Current Favicon</p>
                        <p class="text-xs text-slate-400">Upload a new one below to replace it.</p>
                    </div>
                    <form action="{{ route('admin.settings.remove-favicon') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 border border-red-200 hover:bg-red-50 text-red-600 rounded-xl text-xs font-bold transition-colors">
                            <i class="fa-solid fa-trash-can mr-1"></i> Remove
                        </button>
                    </form>
                </div>
            @endif

            <form action="{{ route('admin.settings.update-favicon') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Upload New Favicon</label>
                    <input type="file" name="favicon" id="favicon" accept=".ico,.png,.svg,.jpg,.jpeg"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm text-slate-600 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('favicon') border-red-400 @enderror">
                    @error('favicon')
                        <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                    <div id="favicon-preview" class="mt-3 hidden">
                        <p class="text-xs font-semibold text-slate-500 mb-2">Preview:</p>
                        <img id="favicon-preview-img" class="h-12 w-12 rounded-xl border border-slate-200 object-contain bg-white shadow-sm">
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-200">
                    <button type="submit" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-sm font-bold transition-all shadow-md shadow-primary-500/20">
                        <i class="fa-solid fa-upload mr-1.5"></i> Upload Favicon
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('favicon')?.addEventListener('change', function(e) {
    const preview = document.getElementById('favicon-preview');
    const img = document.getElementById('favicon-preview-img');
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
