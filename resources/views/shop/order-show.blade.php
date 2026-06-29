@extends('layouts.shop')

@section('title', 'Order Details')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <!-- Breadcrumbs / History Navigation link -->
    <div class="mb-6 flex justify-between items-center">
        <a href="{{ Auth::check() ? route('orders.index') : route('orders.track') }}" class="text-sm font-semibold text-slate-500 hover:text-primary-600 flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left text-xs"></i> Back to list
        </a>
        <span class="text-xs font-bold text-slate-400">Date Placed: {{ $order->created_at->format('M d, Y h:i A') }}</span>
    </div>

    <!-- Order Header Status Card -->
    <div class="bg-white border border-slate-200/80 rounded-[32px] p-6 sm:p-8 shadow-sm mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div>
            <span class="text-xs font-bold uppercase text-slate-400">Order Reference</span>
            <h1 class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ $order->order_number }}</h1>
        </div>
        <div class="flex flex-wrap gap-3">
            <span class="px-4 py-1.5 rounded-full text-xs font-bold border uppercase tracking-wider {{ $order->status_color }}">
                Delivery: {{ $order->status }}
            </span>
            <span class="px-4 py-1.5 rounded-full text-xs font-bold border uppercase tracking-wider {{ $order->payment_status === 'paid' ? 'bg-green-150 border-green-200 text-green-800' : 'bg-yellow-150 border-yellow-200 text-yellow-800' }}">
                Payment: {{ $order->payment_status }}
            </span>
        </div>
    </div>

    <!-- Order Body Grid details -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
        
        <!-- Items in Order -->
        <div class="md:col-span-2 bg-white border border-slate-200/80 rounded-[32px] p-6 sm:p-8 shadow-sm space-y-6">
            <h2 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-3">Products in Order</h2>
            
            <div class="space-y-4">
                @foreach($order->items as $item)
                    <div class="flex items-center justify-between gap-6 pb-4 border-b border-slate-100 last:border-0 last:pb-0">
                        <div class="flex items-center gap-4">
                            <div class="h-16 w-16 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                <img src="{{ $item->product ? $item->product->image_url : 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&auto=format&fit=crop&q=60' }}" alt="{{ $item->product_name }}" class="h-full w-full object-cover">
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 text-sm">
                                    @if($item->product)
                                        <a href="{{ route('products.show', $item->product->slug) }}" class="hover:text-primary-600 transition-colors">{{ $item->product_name }}</a>
                                    @else
                                        {{ $item->product_name }}
                                    @endif
                                </h3>
                                <div class="flex flex-wrap gap-1.5 mt-1">
                                    @if($item->selected_size)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200 text-[10px] font-semibold text-slate-500">
                                            <i class="fa-solid fa-ruler text-[8px]"></i> {{ $item->selected_size }}
                                        </span>
                                    @endif
                                    @if($item->selected_color)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200 text-[10px] font-semibold text-slate-500">
                                            <i class="fa-solid fa-palette text-[8px]"></i> {{ $item->selected_color }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-400 mt-0.5">Qty: {{ $item->quantity }} &times; ৳{{ number_format($item->price, 0) }}</p>
                            </div>
                        </div>
                        <span class="font-extrabold text-slate-900 text-sm">৳{{ number_format($item->total, 0) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Shipping & Details Column -->
        <div class="space-y-8">
            <!-- Customer Card -->
            <div class="bg-white border border-slate-200/80 rounded-[32px] p-6 shadow-sm space-y-4">
                <h3 class="font-bold text-slate-900 border-b border-slate-100 pb-2.5">Customer details</h3>
                <div class="space-y-2 text-sm">
                    <p class="font-bold text-slate-800">{{ $order->customer_name }}</p>
                    @if(!empty($order->customer_email))
                    <p class="text-slate-500 font-medium"><i class="fa-regular fa-envelope mr-1.5 text-slate-400"></i>{{ $order->customer_email }}</p>
                    @endif
                    @if($order->customer_phone)
                        <p class="text-slate-500 font-medium"><i class="fa-solid fa-phone mr-1.5 text-slate-400"></i>{{ $order->customer_phone }}</p>
                    @endif
                </div>
            </div>

            <!-- Shipping Address Card -->
            <div class="bg-white border border-slate-200/80 rounded-[32px] p-6 shadow-sm space-y-4">
                <h3 class="font-bold text-slate-900 border-b border-slate-100 pb-2.5">Shipping Address</h3>
                <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line">{{ $order->shipping_address }}</p>
            </div>

            <!-- Summary breakdown -->
            <div class="bg-white border border-slate-200/80 rounded-[32px] p-6 shadow-sm space-y-4">
                <h3 class="font-bold text-slate-900 border-b border-slate-100 pb-2.5">Order Totals</h3>
                <div class="space-y-2.5 text-sm">
                    <div class="flex justify-between text-slate-500 font-medium">
                        <span>Subtotal</span>
                        <span class="text-slate-950 font-bold">৳{{ number_format($order->subtotal, 0) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-500 font-medium">
                        <span>Shipping Cost</span>
                        <span class="text-slate-950 font-bold">৳{{ number_format($order->shipping_cost, 0) }}</span>
                    </div>
                    <div class="border-t border-slate-100 pt-2.5 flex justify-between font-extrabold text-slate-900">
                        <span>Total Paid</span>
                        <span class="text-primary-600">৳{{ number_format($order->total, 0) }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
