@extends('layouts.admin')

@section('page_title', 'Dashboard Overview')

@section('content')
<!-- Dashboard Metric Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Total Sales -->
    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Revenue</span>
            <p class="text-2xl font-extrabold text-slate-900">৳{{ number_format($totalSales, 0) }}</p>
        </div>
        <span class="p-4 bg-green-50 text-green-600 rounded-2xl">
            <i class="fa-solid fa-money-bill-trend-up text-xl"></i>
        </span>
    </div>

    <!-- Total Orders -->
    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Orders</span>
            <p class="text-2xl font-extrabold text-slate-900">{{ $totalOrders }}</p>
        </div>
        <span class="p-4 bg-blue-50 text-blue-600 rounded-2xl">
            <i class="fa-solid fa-cart-shopping text-xl"></i>
        </span>
    </div>

    <!-- Total Products -->
    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Products</span>
            <p class="text-2xl font-extrabold text-slate-900">{{ $totalProducts }}</p>
        </div>
        <span class="p-4 bg-purple-50 text-purple-600 rounded-2xl">
            <i class="fa-solid fa-box text-xl"></i>
        </span>
    </div>

    <!-- Total Customers -->
    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Customers</span>
            <p class="text-2xl font-extrabold text-slate-900">{{ $totalUsers }}</p>
        </div>
        <span class="p-4 bg-orange-50 text-orange-600 rounded-2xl">
            <i class="fa-solid fa-users text-xl"></i>
        </span>
    </div>

</div>

<!-- Recent Orders & Category Overview Layout -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Recent Orders table -->
    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden flex flex-col justify-between">
        <div>
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-extrabold text-slate-900 text-base">Recent Orders</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-primary-600 hover:text-primary-755">Manage All Orders</a>
            </div>
            
            @if($recentOrders->isEmpty())
                <div class="p-12 text-center text-slate-500 text-sm">
                    No orders have been placed yet.
                </div>
            @else
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-400">Order Number</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-400">Customer</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-400">Total Price</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-400">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @foreach($recentOrders as $order)
                            <tr class="hover:bg-slate-50/55 cursor-pointer" onclick="window.location='{{ route('admin.orders.show', $order->id) }}'">
                                <td class="px-6 py-4 font-bold text-slate-950">{{ $order->order_number }}</td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-800">{{ $order->customer_name }}</p>
                                    @if(!empty($order->customer_email))
                                    <p class="text-slate-400 text-xs mt-0.5">{{ $order->customer_email }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-800">৳{{ number_format($order->total, 0) }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border uppercase tracking-wider {{ $order->status_color }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- Category Breakdown Panel -->
    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-6">
        <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-3">Stock Category Check</h3>
        <div class="space-y-4">
            @foreach($categories as $cat)
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-primary-500"></span>
                        <span class="font-semibold text-slate-700">{{ $cat->name }}</span>
                    </div>
                    <span class="text-xs font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">
                        {{ $cat->products_count }} items
                    </span>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
