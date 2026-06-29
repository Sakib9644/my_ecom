@extends('layouts.shop')

@section('title', 'My Orders')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-extrabold text-slate-900 mb-8">My Orders</h1>

    @if($orders->isEmpty())
        <div class="bg-white border border-slate-200 rounded-[32px] p-16 text-center space-y-6 shadow-sm">
            <div class="inline-flex h-20 w-20 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                <i class="fa-solid fa-box-open text-3xl"></i>
            </div>
            <div class="space-y-2">
                <h3 class="text-xl font-bold text-slate-900">No orders found</h3>
                <p class="text-slate-500 max-w-sm mx-auto">You haven't placed any orders yet. Once you place an order, it will appear here.</p>
            </div>
            <a href="{{ route('products.index') }}" class="inline-flex px-8 py-3.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-2xl transition-all shadow-lg shadow-primary-600/30">
                Start Shopping
            </a>
        </div>
    @else
        <div class="bg-white border border-slate-200/80 rounded-[32px] overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[500px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="p-4 sm:p-6 text-xs font-bold uppercase tracking-wider text-slate-400">Order Number</th>
                            <th class="p-4 sm:p-6 text-xs font-bold uppercase tracking-wider text-slate-400">Date</th>
                            <th class="p-4 sm:p-6 text-xs font-bold uppercase tracking-wider text-slate-400">Total Price</th>
                            <th class="p-4 sm:p-6 text-xs font-bold uppercase tracking-wider text-slate-400">Order Status</th>
                            <th class="p-4 sm:p-6 text-xs font-bold uppercase tracking-wider text-slate-400 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($orders as $order)
                            <tr>
                                <td class="p-4 sm:p-6 text-sm font-extrabold text-slate-900 whitespace-nowrap">{{ $order->order_number }}</td>
                                <td class="p-4 sm:p-6 text-sm text-slate-500 whitespace-nowrap">{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="p-4 sm:p-6 text-sm font-bold text-slate-800 whitespace-nowrap">৳{{ number_format($order->total, 0) }}</td>
                                <td class="p-4 sm:p-6 whitespace-nowrap">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold border uppercase tracking-wider {{ $order->status_color }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="p-4 sm:p-6 text-right whitespace-nowrap">
                                    <a href="{{ route('orders.show', $order->order_number) }}" class="px-4 py-2 border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold rounded-xl text-xs transition-colors inline-block">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
