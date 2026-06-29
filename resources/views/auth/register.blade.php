@extends('layouts.shop')

@section('title', 'Create Account')

@section('content')
<div class="max-w-md mx-auto px-4 py-16 sm:py-24">
    <div class="bg-white border border-slate-200/80 rounded-[32px] p-8 shadow-sm space-y-6">
        
        <div class="text-center space-y-2">
            <h1 class="text-2xl font-extrabold text-slate-900">Create Account</h1>
            <p class="text-xs text-slate-500">Sign up today and start tracking your order history.</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white" placeholder="Sarah Connor">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white" placeholder="sarah@example.com">
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

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase">Confirm Password</label>
                <input type="password" name="password_confirmation" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white" placeholder="••••••••">
            </div>

            <button type="submit" class="w-full py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl text-sm transition-colors shadow-lg shadow-primary-600/20">
                Register
            </button>
        </form>

        <div class="border-t border-slate-100 pt-4 text-center text-xs text-slate-500">
            Already have an account? <a href="{{ route('login') }}" class="text-primary-600 font-semibold hover:text-primary-700">Sign in instead</a>
        </div>

    </div>
</div>
@endsection
