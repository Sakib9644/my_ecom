@extends('layouts.shop')

@section('title', 'Order Success')

@section('content')
<div class="max-w-xl mx-auto px-4 py-16 sm:py-24 text-center">
    <div class="inline-flex h-20 w-20 items-center justify-center rounded-full bg-green-100 text-green-600 mb-8 animate-bounce">
        <i class="fa-regular fa-circle-check text-4xl"></i>
    </div>
    
    <h1 class="text-3xl font-extrabold text-slate-900 mb-3">Thank you for your order!</h1>
    <p class="text-slate-500 text-sm mb-8">We have received your order and are processing it. Below are your order reference details.</p>

    <!-- Details Card -->
    <div class="bg-white border border-slate-200/80 rounded-[32px] p-6 sm:p-8 space-y-4 shadow-sm text-left mb-8">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3.5">
            <span class="text-xs font-bold uppercase text-slate-400">Order Number</span>
            <span class="text-sm font-extrabold text-primary-600 select-all">{{ $order->order_number }}</span>
        </div>
        <div class="flex justify-between items-center border-b border-slate-100 pb-3.5">
            <span class="text-xs font-bold uppercase text-slate-400">Customer Name</span>
            <span class="text-sm font-bold text-slate-800">{{ $order->customer_name }}</span>
        </div>
        @if(!empty($order->customer_email))
        <div class="flex justify-between items-center border-b border-slate-100 pb-3.5">
            <span class="text-xs font-bold uppercase text-slate-400">Email Address</span>
            <span class="text-sm font-bold text-slate-800 text-right truncate max-w-[200px]">{{ $order->customer_email }}</span>
        </div>
        @endif
        <div class="flex justify-between items-center border-b border-slate-100 pb-3.5">
            <span class="text-xs font-bold uppercase text-slate-400">Total Charged</span>
            <span class="text-sm font-extrabold text-slate-900">৳{{ number_format($order->total, 0) }}</span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-xs font-bold uppercase text-slate-400">Payment Type</span>
            <span class="text-xs font-bold uppercase text-primary-800 bg-primary-50 border border-primary-100 px-2.5 py-1 rounded-full">Cash on Delivery</span>
        </div>
    </div>

    <!-- Info Notice -->
    <div class="p-4 bg-primary-50 border border-primary-200 rounded-2xl text-left flex gap-3 mb-8">
        <i class="fa-solid fa-circle-info text-primary-600 text-base mt-0.5 shrink-0"></i>
        <div class="space-y-1">
            <p class="text-xs font-bold text-primary-900">How to track your order?</p>
            <p class="text-xs text-primary-700 leading-relaxed">
                Save your <strong>Order Number</strong> and <strong>Email Address</strong>. You can use them on our <a href="{{ route('orders.track') }}" class="underline font-semibold hover:text-primary-900">Track Order</a> page to inspect delivery status at any time, even without creating an account.
            </p>
        </div>
    </div>

    <div class="flex flex-wrap gap-4 justify-center">
        <!-- Direct tracking preview -->
        <a href="{{ route('orders.show', $order->order_number) }}" class="px-8 py-3.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-2xl shadow-lg shadow-primary-600/30 transition-all text-sm">
            View Order Status
        </a>
        <a href="{{ route('products.index') }}" class="px-8 py-3.5 bg-slate-150 hover:bg-slate-200 border border-slate-300 text-slate-700 font-semibold rounded-2xl transition-all text-sm">
            Continue Shopping
        </a>
    </div>
</div>
@endsection
