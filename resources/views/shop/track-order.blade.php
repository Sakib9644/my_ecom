@extends('layouts.shop')

@section('title', 'Track Your Order')

@section('content')
<div class="max-w-md mx-auto px-4 py-16 sm:py-24">
    <div class="bg-white border border-slate-200/80 rounded-[32px] p-6 sm:p-8 shadow-sm space-y-6">
        
        <div class="text-center space-y-2">
            <div class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-primary-50 text-primary-600">
                <i class="fa-solid fa-truck-fast text-xl"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900">Track Order</h1>
            <p class="text-xs text-slate-500">Track status using your order number and email address.</p>
        </div>

        <form action="{{ route('orders.find') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase">Order Number</label>
                <input type="text" name="order_number" value="{{ old('order_number') }}" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white" placeholder="ORD-649D8C6...">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white" placeholder="customer@example.com">
            </div>

            <button type="submit" class="w-full py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl text-sm transition-colors shadow-lg shadow-primary-600/20 flex items-center justify-center gap-2">
                <i class="fa-solid fa-magnifying-glass"></i> Track Order Status
            </button>
        </form>
    </div>
</div>
@endsection
