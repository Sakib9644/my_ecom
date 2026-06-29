@extends('layouts.admin')

@section('page_title', 'Manage Colors')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <p class="text-sm text-slate-500">Manage color options that appear as dropdown selections when adding or editing products.</p>
    <a href="{{ route('admin.colors.create') }}" class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-primary-500/20">
        <i class="fa-solid fa-plus mr-1"></i> Add Color
    </a>
</div>

<div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm p-4 sm:p-6">
    <table id="colors-table" class="w-full text-left border-collapse datatable-table">
        <thead>
            <tr>
                <th>SL</th>
                <th>Color</th>
                <th>Hex Code</th>
                <th>Swatch</th>
                <th>Order</th>
                <th>Actions</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#colors-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.colors.index') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '40px' },
            { data: 'name', name: 'name' },
            { data: 'hex_code', name: 'hex_code', defaultContent: '—' },
            { data: 'swatch', name: 'swatch', orderable: false, searchable: false },
            { data: 'sort_order', name: 'sort_order' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[0, 'desc']],
        responsive: true,
        pageLength: 20,
        lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/en-GB.json'
        },
        dom: "<'flex flex-wrap items-center justify-between mb-4 gap-3'<'flex items-center gap-2'l><'flex items-center'f>>" +
             "<'overflow-x-auto'tr>" +
             "<'flex flex-wrap items-center justify-between mt-4 gap-3'<'flex items-center'i><'flex items-center'p>>",
    });
});
</script>
@endsection
