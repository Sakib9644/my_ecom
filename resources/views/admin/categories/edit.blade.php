@extends('layouts.admin')

@section('page_title', 'Edit Category: ' . $category->name)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.categories.index') }}" class="text-sm font-semibold text-slate-500 hover:text-primary-600 flex items-center gap-1.5 w-fit">
        <i class="fa-solid fa-arrow-left text-xs"></i> Back to list
    </a>
</div>

<div class="max-w-xl bg-white border border-slate-200 rounded-3xl p-4 sm:p-6 lg:p-8 shadow-sm">
    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Name -->
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase">Category Name</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase">Description (Optional)</label>
            <textarea name="description" rows="3" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">{{ old('description', $category->description) }}</textarea>
            @error('description')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit -->
        <div class="pt-4 border-t border-slate-100 flex gap-4 justify-end">
            <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 border border-slate-300 hover:bg-slate-50 text-slate-750 font-bold rounded-xl text-xs transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl text-xs transition-colors shadow-md shadow-primary-500/10">
                Update Category
            </button>
        </div>
    </form>
</div>
@endsection
