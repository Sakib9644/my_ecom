@extends('layouts.shop')

@section('title', $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
    <!-- Breadcrumbs -->
    <nav class="flex mb-8 text-sm font-semibold text-slate-500 gap-2 flex-wrap">
        <a href="{{ route('home') }}" class="hover:text-primary-600">Home</a>
        <span>/</span>
        <a href="{{ route('products.index') }}" class="hover:text-primary-600">Shop</a>
        <span>/</span>
        <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="hover:text-primary-600">{{ $product->category->name }}</a>
        <span>/</span>
        <span class="text-slate-900 truncate">{{ $product->name }}</span>
    </nav>

    <!-- Product Main Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 bg-white border border-slate-200/80 p-8 sm:p-12 rounded-[32px] shadow-sm mb-16">

        <!-- Image -->
        <div class="space-y-4">
            <div class="aspect-square rounded-2xl overflow-hidden bg-slate-100 border border-slate-200/50">
                <img id="main-product-image" src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover object-center transition-all duration-300">
            </div>
            @php $colorImages = $product->color_images ?? []; @endphp
            @if(!empty($colorImages))
                <div class="flex flex-wrap gap-2" id="image-thumbnails">
                    <button type="button"
                        onclick="selectProductImage(this, '{{ $product->image_url }}')"
                        class="aspect-square w-16 rounded-xl border-2 border-primary-500 overflow-hidden bg-slate-100 hover:opacity-80 transition-all thumb-btn"
                        data-image="{{ $product->image_url }}">
                        <img src="{{ $product->image_url }}" alt="Default" class="h-full w-full object-cover">
                    </button>
                    @foreach(($product->colors ?? []) as $colorName)
                        @if(isset($colorImages[$colorName]))
                            @php
                                $colorImg = $product->getImageForColor($colorName);
                            @endphp
                            <button type="button"
                                onclick="selectProductImage(this, '{{ $colorImg }}')"
                                class="aspect-square w-16 rounded-xl border-2 border-slate-200 overflow-hidden bg-slate-100 hover:opacity-80 hover:border-primary-400 transition-all thumb-btn"
                                data-image="{{ $colorImg }}"
                                data-color="{{ $colorName }}">
                                <img src="{{ $colorImg }}" alt="{{ $colorName }}" class="h-full w-full object-cover">
                            </button>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Details -->
        <div class="flex flex-col justify-between">
            <div class="space-y-5">
                <span class="text-xs font-bold uppercase tracking-wider text-primary-600 bg-primary-50 px-3 py-1.5 rounded-full inline-block">{{ $product->category->name }}</span>

                <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight">{{ $product->name }}</h1>

                <!-- Price -->
                <div class="text-3xl font-extrabold text-slate-900">৳{{ number_format($product->price, 0) }}</div>

                <!-- Description -->
                <p class="text-slate-600 leading-relaxed text-sm">{{ $product->description }}</p>

                <!-- Stock (dynamic) -->
                @php 
                    $hasVariants = !empty($product->sizes) || !empty($product->colors);
                    $totalStock = $product->total_stock; 
                @endphp
                <div class="flex items-center gap-2" id="stock-status">
                    @if($hasVariants)
                        @if($totalStock > 0)
                            <span class="inline-block w-2.5 h-2.5 rounded-full bg-blue-500 animate-pulse"></span>
                            <span class="text-xs font-bold uppercase tracking-wider text-blue-700">Available in Variants</span>
                        @else
                            <span class="inline-block w-2.5 h-2.5 rounded-full bg-red-500"></span>
                            <span class="text-xs font-bold uppercase tracking-wider text-red-700">Out of Stock</span>
                        @endif
                    @else
                        <span class="inline-block w-2.5 h-2.5 rounded-full {{ $totalStock > 0 ? 'bg-green-500' : 'bg-red-500' }}"></span>
                        <span class="text-xs font-bold uppercase tracking-wider {{ $totalStock > 0 ? 'text-green-700' : 'text-red-700' }}">
                            {{ $totalStock > 0 ? "In Stock ({$totalStock} available)" : 'Out of Stock' }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Add to Cart -->
            <div class="pt-8 border-t border-slate-100 mt-8">
                @if($totalStock > 0)
                    <form id="add-to-cart-form" action="{{ route('cart.add', $product->id) }}" method="POST" class="space-y-5">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        {{-- SIZE CHIPS --}}
                        @if(!empty($product->sizes) && count($product->sizes) > 0)
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                    <i class="fa-solid fa-ruler text-slate-400 mr-1"></i> Select Size
                                </label>
                                <div class="flex flex-wrap gap-2" id="size-chips">
                                    @foreach(($product->sizes ?? []) as $size)
                                        <button
                                            type="button"
                                            data-size="{{ $size }}"
                                            onclick="selectSizeChip(this, '{{ $size }}')"
                                            class="size-chip group px-4 py-2 rounded-xl border-2 border-slate-200 text-xs font-bold text-slate-600 hover:border-primary-400 hover:text-primary-700 transition-all"
                                        >
                                            {{ $size }}
                                        </button>
                                    @endforeach
                                </div>
                                <input type="hidden" name="size" id="size-input" value="">
                            </div>
                        @endif

                        {{-- COLOR CHIPS --}}
                        @if(!empty($product->colors) && count($product->colors) > 0)
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                    <i class="fa-solid fa-palette text-slate-400 mr-1"></i> Select Color
                                </label>
                                @php
                                    $dot = function($color) {
                                        return match(true) {
                                            str_contains(strtolower($color), 'black')    => '#1e293b',
                                            str_contains(strtolower($color), 'white')    => '#f8fafc',
                                            str_contains(strtolower($color), 'red')      => '#ef4444',
                                            str_contains(strtolower($color), 'blue')     => '#3b82f6',
                                            str_contains(strtolower($color), 'green')    => '#22c55e',
                                            str_contains(strtolower($color), 'silver') || str_contains(strtolower($color), 'chrome') => '#94a3b8',
                                            str_contains(strtolower($color), 'gold')     => '#fbbf24',
                                            str_contains(strtolower($color), 'rose')     => '#f472b6',
                                            str_contains(strtolower($color), 'brown') || str_contains(strtolower($color), 'chestnut') => '#92400e',
                                            str_contains(strtolower($color), 'tan')      => '#d97706',
                                            str_contains(strtolower($color), 'grey') || str_contains(strtolower($color), 'gray') => '#64748b',
                                            str_contains(strtolower($color), 'teal')     => '#14b8a6',
                                            str_contains(strtolower($color), 'yellow')   => '#facc15',
                                            str_contains(strtolower($color), 'obsidian') => '#0f172a',
                                            str_contains(strtolower($color), 'orange')   => '#f97316',
                                            str_contains(strtolower($color), 'purple') || str_contains(strtolower($color), 'violet') => '#a855f7',
                                            str_contains(strtolower($color), 'navy')     => '#1e3a8a',
                                            str_contains(strtolower($color), 'lavender') => '#c4b5fd',
                                            str_contains(strtolower($color), 'mint')     => '#6ee7b7',
                                            str_contains(strtolower($color), 'neon')     => '#a3e635',
                                            default                                      => '#0ea5e9',
                                        };
                                    };
                                @endphp
                                <div class="flex flex-wrap gap-2" id="color-chips">
                                    @foreach(($product->colors ?? []) as $color)
                                        <button
                                            type="button"
                                            data-color="{{ $color }}"
                                            onclick="selectColorChip(this, '{{ $color }}')"
                                            title="{{ $color }}"
                                            class="color-chip group flex items-center gap-2 px-3 py-1.5 rounded-xl border-2 border-slate-200 text-xs font-semibold text-slate-600 hover:border-primary-400 transition-all"
                                        >
                                            <span class="h-3.5 w-3.5 rounded-full ring-1 ring-black/10 shrink-0 transition-transform group-hover:scale-110"
                                                  style="background-color: {{ $dot($color) }}"></span>
                                            {{ $color }}
                                        </button>
                                    @endforeach
                                </div>
                                <input type="hidden" name="color" id="color-input" value="">
                            </div>
                        @endif

                        {{-- QUANTITY + ADD / BUY NOW --}}
                        <div class="flex flex-wrap items-center gap-3 pt-2">
                            <div class="flex items-center border-2 border-slate-200 rounded-2xl overflow-hidden bg-white">
                                <button type="button" onclick="decrementQty()" class="h-12 w-12 flex items-center justify-center hover:bg-slate-50 text-slate-600 transition-colors focus:outline-none">
                                    <i class="fa-solid fa-minus text-xs"></i>
                                </button>
                                <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $totalStock }}"
                                    class="w-12 text-center border-0 font-bold text-sm focus:ring-0 focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                <button type="button" onclick="incrementQty()" class="h-12 w-12 flex items-center justify-center hover:bg-slate-50 text-slate-600 transition-colors focus:outline-none">
                                    <i class="fa-solid fa-plus text-xs"></i>
                                </button>
                            </div>

                            <button type="submit" id="add-to-cart-btn" class="px-6 py-3.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-2xl shadow-lg shadow-primary-600/30 transition-all flex items-center justify-center gap-2 hover:-translate-y-0.5 active:scale-95">
                                <i class="fa-solid fa-cart-shopping"></i> Add to Cart
                            </button>

                            <button type="button" id="buy-now-btn"
                                onclick="submitAddToCart(true)"
                                class="px-6 py-3.5 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-bold rounded-2xl shadow-lg shadow-emerald-500/30 transition-all flex items-center justify-center gap-2 hover:-translate-y-0.5 hover:shadow-emerald-500/40 active:scale-95">
                                <i class="fa-solid fa-bolt"></i> Buy Now
                            </button>
                        </div>
                    </form>
                @else
                    <button class="w-full sm:w-auto px-8 py-3.5 bg-slate-200 text-slate-400 font-bold rounded-2xl cursor-not-allowed" disabled>
                        Temporarily Out of Stock
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if(!$relatedProducts->isEmpty())
        <div class="mb-16">
            <h2 class="text-2xl font-extrabold text-slate-900 mb-8">Related Products</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $related)
                    <div class="group bg-white border border-slate-200/80 rounded-2xl overflow-hidden hover:shadow-lg transition-all flex flex-col">
                        <div class="aspect-square w-full overflow-hidden bg-slate-100">
                            <img src="{{ $related->image_url }}" alt="{{ $related->name }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="p-4 flex-grow flex flex-col justify-between">
                            <h3 class="font-bold text-slate-900 text-sm mb-1 hover:text-primary-600">
                                <a href="{{ route('products.show', $related->slug) }}">{{ $related->name }}</a>
                            </h3>
                            <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-100">
                                <span class="font-bold text-slate-900">৳{{ number_format($related->price, 0) }}</span>
                                <span class="text-xs font-semibold text-primary-600 uppercase">{{ $related->category->name }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    // ── Color Image Map (built server-side) ──
    const COLOR_IMAGES = {
        @php $colorImages = $product->color_images ?? []; @endphp
        @foreach(($product->colors ?? []) as $colorName)
            @if(isset($colorImages[$colorName]))
                '{{ $colorName }}': '{{ $product->getImageForColor($colorName) }}',
            @endif
        @endforeach
    };
    const DEFAULT_IMAGE = '{{ $product->image_url }}';
    const PRODUCT_ID = {{ $product->id }};
    const VARIANT_STOCK_URL = '{{ route('products.variant-stock', $product->id) }}';
    const HAS_VARIANTS = {{ (!empty($product->sizes) || !empty($product->colors)) ? 'true' : 'false' }};

    // ── CSRF token helper ──
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // ── Current variant stock (fetched from server) ──
    let currentStock = {{ $totalStock }};

    // ── Update stock display dynamically ──
    function updateStockDisplay(stock) {
        currentStock = stock;
        const statusEl = document.getElementById('stock-status');
        const qtyInput = document.getElementById('quantity');
        const plusBtn = qtyInput?.nextElementSibling;

        if (statusEl) {
            if (stock > 0) {
                statusEl.innerHTML = `
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-green-500"></span>
                    <span class="text-xs font-bold uppercase tracking-wider text-green-700">In Stock (${stock} available)</span>
                `;
            } else {
                statusEl.innerHTML = `
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-red-500"></span>
                    <span class="text-xs font-bold uppercase tracking-wider text-red-700">Out of Stock</span>
                `;
            }
        }

        if (qtyInput) {
            qtyInput.max = stock;
            qtyInput.value = stock > 0 ? Math.min(parseInt(qtyInput.value) || 1, stock) : 1;
        }

        // Update plus button onclick max
        if (plusBtn && plusBtn.tagName === 'BUTTON') {
            plusBtn.setAttribute('onclick', `incrementQty()`);
        }
    }

    // ── Fetch variant stock via AJAX ──
    function fetchVariantStock() {
        if (!HAS_VARIANTS) return;

        const sizeVal = document.getElementById('size-input')?.value || '';
        const colorVal = document.getElementById('color-input')?.value || '';

        // Only fetch if at least one variant is selected
        if (!sizeVal && !colorVal) return;

        const params = new URLSearchParams();
        if (sizeVal) params.append('size', sizeVal);
        if (colorVal) params.append('color', colorVal);

        fetch(`${VARIANT_STOCK_URL}?${params.toString()}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                updateStockDisplay(data.stock);
            }
        })
        .catch(() => {});
    }

    // ── Update cart badge ──
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

    // ── AJAX Add to Cart / Buy Now ──
    function submitAddToCart(buyNow = false) {
        const form = document.getElementById('add-to-cart-form');
        const formData = new FormData(form);
        const btn = buyNow ? document.getElementById('buy-now-btn') : document.getElementById('add-to-cart-btn');

        // Validate variant selection & quantity
        const hasSizes = document.getElementById('size-input');
        const hasColors = document.getElementById('color-input');
        const sizeVal = hasSizes ? hasSizes.value : '';
        const colorVal = hasColors ? hasColors.value : '';
        const qty = parseInt(document.getElementById('quantity').value) || 0;

        if ((sizeVal || colorVal) && qty < 1) {
            Swal.fire({
                icon: 'warning',
                title: 'Quantity Required',
                text: 'Please enter a quantity (minimum 1) for the selected variant.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#0ea5e9',
                background: '#fffbeb',
                color: '#92400e',
                iconColor: '#f59e0b',
                customClass: { popup: 'rounded-2xl shadow-2xl border border-amber-200' }
            });
            return;
        }

        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> ' + (buyNow ? 'Processing...' : 'Adding...');

        fetch(form.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                updateCartBadge(data.cart_count);
                if (buyNow) {
                    window.location.href = '{{ route('checkout.index') }}';
                } else {
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
                }
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
        .catch(err => {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Something went wrong. Please try again.',
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

    // ── Prevent normal form submission + auto-select first size/color ──
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('add-to-cart-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                submitAddToCart(false);
            });

            // Auto-select first size chip
            const firstSizeChip = document.querySelector('.size-chip');
            if (firstSizeChip) {
                const firstSize = firstSizeChip.getAttribute('data-size');
                selectSizeChip(firstSizeChip, firstSize);
            }

            // Auto-select first color chip
            const firstColorChip = document.querySelector('.color-chip');
            if (firstColorChip) {
                const firstColor = firstColorChip.getAttribute('data-color');
                selectColorChip(firstColorChip, firstColor);
            }
        }
    });

    // ── Quantity controls ──
    function incrementQty() {
        const el = document.getElementById('quantity');
        const max = parseInt(el.max) || currentStock;
        if (parseInt(el.value) < max) el.value = parseInt(el.value) + 1;
    }
    function decrementQty() {
        const el = document.getElementById('quantity');
        if (parseInt(el.value) > 1) el.value = parseInt(el.value) - 1;
    }

    // ── Swap main product image ──
    function swapMainImage(src) {
        const img = document.getElementById('main-product-image');
        if (img) {
            img.style.opacity = '0.5';
            setTimeout(() => {
                img.src = src;
                img.style.opacity = '1';
            }, 150);
        }
    }

    // ── Thumbnail highlight ──
    function selectProductImage(btn, src) {
        document.querySelectorAll('.thumb-btn').forEach(t => {
            t.classList.remove('border-primary-500');
            t.classList.add('border-slate-200');
        });
        btn.classList.remove('border-slate-200');
        btn.classList.add('border-primary-500');
        swapMainImage(src);
    }

    // ── Size chip → sets hidden input + active state + fetches variant stock ──
    function selectSizeChip(btn, size) {
        document.querySelectorAll('.size-chip').forEach(c => {
            c.classList.remove('border-primary-500', 'bg-primary-50', 'text-primary-700');
            c.classList.add('border-slate-200', 'text-slate-600');
        });
        btn.classList.remove('border-slate-200', 'text-slate-600');
        btn.classList.add('border-primary-500', 'bg-primary-50', 'text-primary-700');
        document.getElementById('size-input').value = size;
        fetchVariantStock();
    }

    // ── Color chip → sets hidden input + swaps image + fetches variant stock ──
    function selectColorChip(btn, color) {
        document.getElementById('color-input').value = color;

        if (COLOR_IMAGES[color]) {
            swapMainImage(COLOR_IMAGES[color]);
        } else {
            swapMainImage(DEFAULT_IMAGE);
        }

        document.querySelectorAll('.thumb-btn').forEach(t => {
            if (t.dataset.color === color) {
                selectProductImage(t, COLOR_IMAGES[color] || DEFAULT_IMAGE);
            }
        });

        document.querySelectorAll('.color-chip').forEach(c => {
            c.classList.remove('border-primary-500', 'bg-primary-50', 'text-primary-700');
            c.classList.add('border-slate-200', 'text-slate-600');
        });
        btn.classList.remove('border-slate-200', 'text-slate-600');
        btn.classList.add('border-primary-500', 'bg-primary-50', 'text-primary-700');
        fetchVariantStock();
    }
</script>
@endsection
