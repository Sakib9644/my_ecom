@extends('layouts.admin')

@section('page_title', 'Product Inventory')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <p class="text-sm text-slate-500">Manage your product catalog, upload media images, and check inventory stock levels.</p>
    <a href="{{ route('admin.products.create') }}" class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-primary-500/20">
        <i class="fa-solid fa-plus mr-1"></i> Add Product
    </a>
</div>

<div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm p-4 sm:p-6">
    <table id="products-table" class="w-full text-left border-collapse datatable-table">
        <thead>
            <tr>
                <th>SL</th>
                <th></th>
                <th>Product</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Featured</th>
                <th>Actions</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#products-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.products.index') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '40px' },
            { data: 'image', name: 'image', orderable: false, searchable: false, width: '60px' },
            { data: 'name', name: 'name' },
            { data: 'category_name', name: 'category.name', orderable: false },
            { data: 'price', name: 'price', render: function(d) { return '৳' + parseFloat(d).toFixed(0); } },
            { data: 'stock_badge', name: 'stock_badge', orderable: false, searchable: false },
            { data: 'featured_badge', name: 'featured_badge', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [],
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/en-GB.json'
        },
        dom: "<'flex flex-wrap items-center justify-between mb-4 gap-3'<'flex items-center gap-2'l><'flex items-center'f>>" +
             "<'overflow-x-auto'tr>" +
             "<'flex flex-wrap items-center justify-between mt-4 gap-3'<'flex items-center'i><'flex items-center'p>>",
    });
});

function toggleFeatured(productId, btn) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    btn.disabled = true;

    fetch('{{ url('/admin/products') }}/' + productId + '/toggle-featured', {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            if (data.is_featured) {
                btn.outerHTML = '<button onclick="toggleFeatured(' + productId + ', this)" class="px-2 py-0.5 rounded-md text-xs font-bold bg-primary-50 text-primary-700 border border-primary-100 hover:bg-primary-100 transition-colors cursor-pointer"><i class="fa-solid fa-star text-[10px] mr-1"></i>Yes</button>';
            } else {
                btn.outerHTML = '<button onclick="toggleFeatured(' + productId + ', this)" class="text-slate-400 font-semibold hover:text-primary-600 transition-colors cursor-pointer">-</button>';
            }
            Swal.fire({ icon: 'success', title: 'Updated!', text: 'Featured status updated.', timer: 1500, showConfirmButton: false, toast: true, position: 'top-end' });
        }
    })
    .catch(() => { btn.disabled = false; });
}
</script>
@endsection
