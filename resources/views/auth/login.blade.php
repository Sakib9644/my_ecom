@extends('layouts.shop')

@section('title', 'Sign In')

@section('content')
<div class="max-w-md mx-auto px-4 py-16 sm:py-24">
    <div class="bg-white border border-slate-200/80 rounded-[32px] p-8 shadow-sm space-y-6">
        
        <div class="text-center space-y-2">
            <h1 class="text-2xl font-extrabold text-slate-900">Welcome Back</h1>
            <p class="text-xs text-slate-500">Sign in to your customer account to access order logs.</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white" placeholder="customer@example.com">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase">Password</label>
                <input type="password" name="password" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white" placeholder="••••••••">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer font-medium text-slate-500">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-primary-600 focus:ring-primary-500 h-4 w-4">
                    <span>Remember me</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl text-sm transition-colors shadow-lg shadow-primary-600/20">
                Sign In
            </button>
        </form>

        <div class="border-t border-slate-100 pt-4 text-center text-xs text-slate-500">
            Don't have an account? <a href="{{ route('register') }}" class="text-primary-600 font-semibold hover:text-primary-700">Create one now</a>
        </div>

    </div>
</div>
@endsection
