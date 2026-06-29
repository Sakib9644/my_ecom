@extends('layouts.admin')

@section('page_title', 'Order Details: ' . $order->order_number)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-slate-500 hover:text-primary-600 flex items-center gap-1.5 w-fit">
        <i class="fa-solid fa-arrow-left text-xs"></i> Back to list
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
    
    <!-- Left: Order Summary & items -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Products list card -->
        <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
            <h2 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-3">Products Ordered</h2>
            
            <div class="space-y-4">
                @foreach($order->items as $item)
                    <div class="flex items-center justify-between gap-6 pb-4 border-b border-slate-100 last:border-0 last:pb-0">
                        <div class="flex items-center gap-4">
                            <div class="h-16 w-16 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                <img src="{{ $item->product ? $item->product->image_url : 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&auto=format&fit=crop&q=60' }}" alt="{{ $item->product_name }}" class="h-full w-full object-cover">
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 text-sm">
                                    {{ $item->product_name }}
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
                                <p class="text-xs text-slate-450 mt-0.5">Qty: {{ $item->quantity }} &times; ৳{{ number_format($item->price, 0) }}</p>
                            </div>
                        </div>
                        <span class="font-extrabold text-slate-900 text-sm">৳{{ number_format($item->total, 0) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Totals Card -->
        <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
            <h2 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-3">Financial Summary</h2>
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
                    <span>Grand Total</span>
                    <span class="text-primary-600">৳{{ number_format($order->total, 0) }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Right: Update Status panel -->
    <div class="space-y-6">
        
        <!-- Status management Form -->
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-6">
            <h3 class="font-bold text-slate-900 border-b border-slate-100 pb-2.5">Update Order Status</h3>
            
            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')

                <!-- Delivery status -->
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase">Delivery Status</label>
                    <select name="status" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Payment status -->
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase">Payment status</label>
                    <select name="payment_status" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
                        <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>

                <button type="submit" class="w-full py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl text-xs transition-colors shadow-md shadow-primary-500/10">
                    Apply Status Changes
                </button>
            </form>
        </div>

        <!-- Customer detail card -->
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-4">
            <h3 class="font-bold text-slate-900 border-b border-slate-100 pb-2.5">Customer details</h3>
            <div class="space-y-2 text-xs">
                <p class="font-bold text-slate-800 text-sm">{{ $order->customer_name }}</p>
                @if(!empty($order->customer_email))
                <p class="text-slate-500"><i class="fa-regular fa-envelope mr-1.5 text-slate-400"></i>{{ $order->customer_email }}</p>
                @endif
                @if($order->customer_phone)
                    <p class="text-slate-500"><i class="fa-solid fa-phone mr-1.5 text-slate-400"></i>{{ $order->customer_phone }}</p>
                @endif
                <div class="pt-2 border-t border-slate-100 mt-2">
                    <span class="text-slate-400 font-bold uppercase tracking-wider block text-[10px] mb-1">Shipping Address</span>
                    <p class="text-slate-600 whitespace-pre-line leading-relaxed">{{ $order->shipping_address }}</p>
                </div>
                @if($order->notes)
                    <div class="pt-2 border-t border-slate-100 mt-2">
                        <span class="text-slate-400 font-bold uppercase tracking-wider block text-[10px] mb-1">Order Notes</span>
                        <p class="text-slate-600 leading-relaxed">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

    </div>

</div>
@endsection
