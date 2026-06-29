@extends('layouts.shop')

@section('title', 'Browse Catalog')

@section('content')
<div class="bg-slate-100/50 py-10 border-b border-slate-200/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold text-slate-900">Store Catalog</h1>
        <p class="text-slate-500 mt-1">Explore all premium products and tech gear.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Filters Sidebar -->
        <aside class="w-full lg:w-64 shrink-0 space-y-6">
            <form action="{{ route('products.index') }}" method="GET" class="space-y-6">
                <!-- Preserve Search if coming from navbar -->
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <!-- Category Filter -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 space-y-4">
                    <h3 class="font-bold text-slate-900 uppercase tracking-wider text-xs border-b border-slate-100 pb-2">Categories</h3>
                    <div class="space-y-2.5">
                        <label class="flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-slate-900 cursor-pointer">
                            <input type="radio" name="category" value="" {{ !request('category') ? 'checked' : '' }} onchange="this.form.submit()" class="h-4 w-4 rounded-full border-slate-300 text-primary-600 focus:ring-primary-500">
                            <span>All Categories</span>
                        </label>
                        @foreach($categories as $category)
                            <label class="flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-slate-900 cursor-pointer">
                                <input type="radio" name="category" value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'checked' : '' }} onchange="this.form.submit()" class="h-4 w-4 rounded-full border-slate-300 text-primary-600 focus:ring-primary-500">
                                <span>{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Price Range Filter -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 space-y-4">
                    <h3 class="font-bold text-slate-900 uppercase tracking-wider text-xs border-b border-slate-100 pb-2">Price Range</h3>
                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1">Min (৳)</label>
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="0" class="w-full rounded-xl border border-slate-300 px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1">Max (৳)</label>
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="500" class="w-full rounded-xl border border-slate-300 px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                    </div>
                    <button type="submit" class="w-full py-2 bg-slate-950 hover:bg-primary-600 text-white rounded-xl text-xs font-bold transition-colors">
                        Apply Price Filter
                    </button>
                </div>

                <!-- Sorting Filter -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 space-y-4">
                    <h3 class="font-bold text-slate-900 uppercase tracking-wider text-xs border-b border-slate-100 pb-2">Sort By</h3>
                    <select name="sort" onchange="this.form.submit()" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Arrivals</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                </div>
            </form>
        </aside>

        <!-- Product Listings -->
        <div class="flex-grow space-y-8">
            <!-- Search Status Header -->
            @if(request('search') || request('category') || request('min_price') || request('max_price'))
                <div class="flex flex-wrap items-center justify-between gap-4 p-4 border border-slate-200 bg-slate-50 rounded-2xl">
                    <div class="text-sm font-medium text-slate-600">
                        Showing results for: 
                        @if(request('search')) <span class="bg-slate-200 text-slate-800 px-2 py-0.5 rounded-lg text-xs font-bold mr-1">"{{ request('search') }}"</span> @endif
                        @if(request('category')) <span class="bg-slate-200 text-slate-800 px-2 py-0.5 rounded-lg text-xs font-bold mr-1">Category: {{ request('category') }}</span> @endif
                        @if(request('min_price') || request('max_price')) <span class="bg-slate-200 text-slate-800 px-2 py-0.5 rounded-lg text-xs font-bold mr-1">Price: ৳{{ request('min_price', 0) }} - ৳{{ request('max_price', 'Any') }}</span> @endif
                    </div>
                    <a href="{{ route('products.index') }}" class="text-xs font-bold text-red-600 hover:text-red-700 flex items-center gap-1">
                        <i class="fa-solid fa-xmark"></i> Clear Filters
                    </a>
                </div>
            @endif

            <!-- Products Grid -->
            @if($products->isEmpty())
                <div class="bg-white border border-slate-200 rounded-3xl p-16 text-center space-y-4">
                    <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">No products found</h3>
                    <p class="text-slate-500 max-w-sm mx-auto">We couldn't find any products matching your selection. Try clearing your filters or exploring another search query.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="group relative bg-white border border-slate-200/80 rounded-3xl overflow-hidden hover:shadow-xl transition-all flex flex-col cursor-pointer" onclick="window.location='{{ route('products.show', $product->slug) }}'">
                            <!-- Image -->
                            <div class="aspect-square w-full overflow-hidden bg-slate-100 relative">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover object-center group-hover:scale-105 transition-transform duration-500">
                                @if($product->total_stock <= 0)
                                    <span class="absolute top-3 left-3 bg-red-600/90 backdrop-blur-sm text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider shadow-lg">Out of Stock</span>
                                @elseif(!empty($product->sizes) || !empty($product->colors))
                                    <span class="absolute top-3 left-3 bg-blue-500/90 backdrop-blur-sm text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider shadow-lg">Variants</span>
                                @elseif($product->stock <= 5)
                                    <span class="absolute top-3 left-3 bg-orange-500/90 backdrop-blur-sm text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider shadow-lg">Low Stock</span>
                                @endif
                            </div>
                            
                            <!-- Content -->
                            <div class="p-5 flex-grow flex flex-col justify-between">
                                <div>
                                    <div class="flex justify-between items-center gap-2 mb-2">
                                        <span class="text-xs font-semibold uppercase tracking-wider text-primary-600">{{ $product->category->name }}</span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 hover:text-primary-600 transition-colors text-base mb-1">{{ $product->name }}</h3>
                                    <p class="text-xs text-slate-500 line-clamp-2 mb-4">{{ $product->description }}</p>
                                </div>
                                
                                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                    <span class="text-lg font-extrabold text-slate-900">৳{{ number_format($product->price, 0) }}</span>
                                    
                                    @if(!empty($product->sizes) || !empty($product->colors))
                                        <a href="{{ route('products.show', $product->slug) }}" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold rounded-xl transition-all duration-200 flex items-center gap-1.5 hover:shadow-lg hover:shadow-blue-600/30 active:scale-95" onclick="event.stopPropagation()">
                                            <i class="fa-solid fa-list text-xs"></i>
                                            <span class="hidden sm:inline">Options</span>
                                        </a>
                                    @elseif($product->stock > 0)
                                        <button onclick="event.stopPropagation(); ajaxAddToCart({{ $product->id }}, this)" class="px-3.5 py-1.5 bg-slate-950 hover:bg-primary-600 text-white text-[10px] font-bold rounded-xl transition-all duration-200 flex items-center gap-1.5 hover:shadow-lg hover:shadow-primary-600/30 active:scale-95">
                                            <i class="fa-solid fa-cart-plus text-xs"></i>
                                            <span class="hidden sm:inline">Add</span>
                                        </button>
                                    @else
                                        <button class="px-3.5 py-2 bg-slate-200 text-slate-400 text-[10px] font-bold rounded-xl cursor-not-allowed flex items-center gap-1.5" disabled>
                                            <i class="fa-regular fa-circle-xmark"></i> Unavail
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination links -->
                <div class="pt-10 border-t border-slate-200">
                    {{ $products->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
function updateCartBadge(count) {
    let badge = document.getElementById('cart-count');
    if (badge) {
        badge.textContent = count;
    } else if (count > 0) {
        const cartLink = document.querySelector('nav a[href$="/cart"]');
        if (cartLink) {
            badge = document.createElement('span');
            badge.id = 'cart-count';
            badge.className = 'absolute top-0 right-0 flex h-5 w-5 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white ring-2 ring-white';
            badge.textContent = count;
            cartLink.appendChild(badge);
        }
    }
}

function ajaxAddToCart(productId, btn) {
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

    fetch('{{ url('/cart/add') }}/' + productId, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            updateCartBadge(data.cart_count);

            Swal.fire({
                icon: 'success',
                title: 'Added to Cart!',
                text: data.message || 'Product added to cart!',
                confirmButtonText: 'Continue Shopping',
                confirmButtonColor: '#0ea5e9',
                background: '#f0fdf4',
                color: '#166534',
                iconColor: '#16a34a',
                customClass: { popup: 'rounded-2xl shadow-2xl border border-green-200' }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: data.message || 'Could not add to cart.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc2626',
                background: '#fef2f2',
                color: '#991b1b',
                iconColor: '#dc2626',
                customClass: { popup: 'rounded-2xl shadow-2xl border border-red-200' }
            });
        }
    })
    .catch(() => {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Something went wrong.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#dc2626',
            background: '#fef2f2',
            color: '#991b1b',
            iconColor: '#dc2626',
            customClass: { popup: 'rounded-2xl shadow-2xl border border-red-200' }
        });
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
    });
}
</script>
@endpush
