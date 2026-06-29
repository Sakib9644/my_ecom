@extends('layouts.admin')

@section('page_title', 'Registered Users')

@section('content')
<div class="mb-6">
    <p class="text-sm text-slate-500">Inspect registered customer accounts and administrator users.</p>
</div>

<div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm p-4 sm:p-6">
    <table id="users-table" class="w-full text-left border-collapse datatable-table">
        <thead>
            <tr>
                <th>SL</th>
                <th>Name</th>
                <th>Email Address</th>
                <th>Role</th>
                <th>Joined Date</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.users.index') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '40px' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'role_badge', name: 'role_badge', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at' },
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
});
</script>
@endsection
