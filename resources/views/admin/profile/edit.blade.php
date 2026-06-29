@extends('layouts.admin')

@section('page_title', 'My Profile')

@section('content')
<div class="max-w-2xl mx-auto space-y-8">
    <!-- Avatar Section -->
    <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">
        <div class="p-6 sm:p-8">
            <h2 class="text-lg font-bold text-slate-900 mb-1">Profile Picture</h2>
            <p class="text-sm text-slate-500 mb-6">Update your admin profile photo.</p>

            <form action="{{ route('admin.profile.avatar') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                    <!-- Current Avatar -->
                    <div class="shrink-0">
                        <div class="h-20 w-20 rounded-2xl overflow-hidden bg-slate-100 border border-slate-200 shadow-sm">
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                        </div>
                    </div>

                    <div class="flex-grow space-y-3">
                        <input type="file" name="avatar" id="avatar" accept="image/*" required
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm text-slate-600 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('avatar') border-red-400 @enderror">
                        @error('avatar')
                            <p class="text-xs font-semibold text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-slate-400">JPG, PNG, WebP. Max 2MB.</p>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-slate-200">
                    <button type="submit" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-sm font-bold transition-all shadow-md shadow-primary-500/20">
                        <i class="fa-solid fa-upload mr-1.5"></i> Upload Photo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Password Section -->
    <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">
        <div class="p-6 sm:p-8">
            <h2 class="text-lg font-bold text-slate-900 mb-1">Change Password</h2>
            <p class="text-sm text-slate-500 mb-6">Update your admin account password.</p>

            <form action="{{ route('admin.profile.password') }}" method="POST">
                @csrf

                <div class="space-y-5">
                    <!-- Current Password -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Current Password</label>
                        <input type="password" name="current_password" required
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('current_password') border-red-400 @enderror" placeholder="Enter your current password">
                        @error('current_password')
                            <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">New Password</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('password') border-red-400 @enderror" placeholder="Enter new password">
                        @error('password')
                            <p class="mt-1 text-xs font-semibold text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm New Password -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Confirm New Password</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Confirm new password">
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-slate-200">
                    <button type="submit" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-sm font-bold transition-all shadow-md shadow-primary-500/20">
                        <i class="fa-solid fa-key mr-1.5"></i> Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
