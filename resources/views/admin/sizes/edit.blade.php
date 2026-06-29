@extends('layouts.admin')

@section('page_title', 'Edit Size: ' . $size->name)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.sizes.index') }}" class="text-sm font-semibold text-slate-500 hover:text-primary-600 flex items-center gap-1.5 w-fit">
        <i class="fa-solid fa-arrow-left text-xs"></i> Back to list
    </a>
</div>

<div class="max-w-xl bg-white border border-slate-200 rounded-3xl p-4 sm:p-6 lg:p-8 shadow-sm">
    <form action="{{ route('admin.sizes.update', $size->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Name -->
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase">Size Name</label>
            <input type="text" name="name" value="{{ old('name', $size->name) }}" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Category -->
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase">Category <span class="text-slate-400 font-normal normal-case">(optional)</span></label>
            <select name="category" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
                <option value="">— No category —</option>
                <option value="general" {{ old('category', $size->category) == 'general' ? 'selected' : '' }}>General</option>
                <option value="shoe" {{ old('category', $size->category) == 'shoe' ? 'selected' : '' }}>Shoe</option>
                <option value="ring" {{ old('category', $size->category) == 'ring' ? 'selected' : '' }}>Ring</option>
                <option value="watch" {{ old('category', $size->category) == 'watch' ? 'selected' : '' }}>Watch</option>
                <option value="clothing" {{ old('category', $size->category) == 'clothing' ? 'selected' : '' }}>Clothing</option>
            </select>
            @error('category')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Sort Order -->
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase">Sort Order</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $size->sort_order) }}" min="0" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
            @error('sort_order')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit -->
        <div class="pt-4 border-t border-slate-100 flex gap-4 justify-end">
            <a href="{{ route('admin.sizes.index') }}" class="px-5 py-2.5 border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold rounded-xl text-xs transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl text-xs transition-colors shadow-md shadow-primary-500/10">
                Update Size
            </button>
        </div>
    </form>
</div>
@endsection
