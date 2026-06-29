@extends('layouts.admin')

@section('page_title', 'Manage Orders')

@section('content')
<div class="mb-6 flex flex-wrap gap-4 items-center justify-between">
    <p class="text-sm text-slate-500">Track and dispatch customer orders, update delivery status, and inspect transaction summaries.</p>
    <div class="flex items-center gap-3">
        <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Status:</label>
        <select id="order-status-filter" class="rounded-xl border border-slate-300 px-4 py-2 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white">
            <option value="">All Orders</option>
            <option value="pending">Pending</option>
            <option value="processing">Processing</option>
            <option value="shipped">Shipped</option>
            <option value="delivered">Delivered</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>
</div>

<div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm p-4 sm:p-6">
    <table id="orders-table" class="w-full text-left border-collapse datatable-table">
        <thead>
            <tr>
                <th>SL</th>
                <th>Order Number</th>
                <th>Customer</th>
                <th>Date Placed</th>
                <th>Grand Total</th>
                <th>Order Status</th>
                <th>Payment</th>
                <th>Actions</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    var table = $('#orders-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('admin.orders.index') }}',
            data: function(d) {
                d.status = $('#order-status-filter').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '40px' },
            { data: 'order_number', name: 'order_number' },
            { data: 'customer', name: 'customer', orderable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'total_formatted', name: 'total_formatted', orderable: false },
            { data: 'status_badge', name: 'status_badge', orderable: false, searchable: false },
            { data: 'payment_badge', name: 'payment_badge', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[0, 'desc']],
        responsive: true,
        pageLength: 15,
        lengthMenu: [[10, 15, 25, 50, -1], [10, 15, 25, 50, 'All']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/en-GB.json'
        },
        dom: "<'flex flex-wrap items-center justify-between mb-4 gap-3'<'flex items-center gap-2'l><'flex items-center'f>>" +
             "<'overflow-x-auto'tr>" +
             "<'flex flex-wrap items-center justify-between mt-4 gap-3'<'flex items-center'i><'flex items-center'p>>",
    });

    $('#order-status-filter').on('change', function() {
        table.ajax.reload();
    });
});
</script>
@endsection
