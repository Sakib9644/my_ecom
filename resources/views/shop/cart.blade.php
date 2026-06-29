@extends('layouts.shop')

@section('title', 'Shopping Cart')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-extrabold text-slate-900 mb-8">Shopping Cart</h1>

    @if(empty($cart))
        <div class="bg-white border border-slate-200 rounded-[32px] p-16 text-center space-y-6 shadow-sm">
            <div class="inline-flex h-20 w-20 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                <i class="fa-solid fa-basket-shopping text-3xl"></i>
            </div>
            <div class="space-y-2">
                <h3 class="text-xl font-bold text-slate-900">Your cart is empty</h3>
                <p class="text-slate-500 max-w-sm mx-auto">Looks like you haven't added any products yet. Explore our collection!</p>
            </div>
            <a href="{{ route('products.index') }}" class="inline-flex px-8 py-3.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-2xl transition-all shadow-lg shadow-primary-600/30">
                Browse Products
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Cart Items List -->
            <div class="lg:col-span-2 space-y-4">
                @foreach($cart as $cartKey => $details)
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-6 shadow-sm hover:shadow-md transition-shadow relative" data-cart-key="{{ $cartKey }}">
                        
                        <!-- Left: Product Info -->
                        <div class="flex items-center gap-4">
                            <div class="h-20 w-20 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                <img src="{{ $details['image'] }}" alt="{{ $details['name'] }}" class="h-full w-full object-cover">
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-base hover:text-primary-600 transition-colors">
                                    <a href="{{ route('products.show', $details['slug']) }}">{{ $details['name'] }}</a>
                                </h3>
                                <!-- Variant Badges -->
                                <div class="flex flex-wrap gap-1.5 mt-1.5">
                                    @if(!empty($details['selected_size']))
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg bg-slate-100 border border-slate-200 text-xs font-semibold text-slate-600">
                                            <i class="fa-solid fa-ruler text-[9px] text-slate-400"></i> {{ $details['selected_size'] }}
                                        </span>
                                    @endif
                                    @if(!empty($details['selected_color']))
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg bg-slate-100 border border-slate-200 text-xs font-semibold text-slate-600">
                                            <i class="fa-solid fa-palette text-[9px] text-slate-400"></i> {{ $details['selected_color'] }}
                                        </span>
                                    @endif
                                </div>
                                <span class="text-sm font-extrabold text-slate-950 mt-1.5 block">৳{{ number_format($details['price'], 0) }}</span>
                            </div>
                        </div>

                        <!-- Right: Controls -->
                        <div class="flex items-center justify-between sm:justify-end gap-6 border-t border-slate-100 pt-4 sm:pt-0 sm:border-0">
                            <!-- Quantity selector -->
                            <div class="flex items-center border border-slate-300 rounded-xl p-0.5 bg-slate-50 w-28 justify-between">
                                <button type="button" onclick="updateQty('{{ $cartKey }}', -1)" class="h-8 w-8 flex items-center justify-center rounded-lg hover:bg-slate-200 text-slate-600 transition-colors">
                                    <i class="fa-solid fa-minus text-[10px]"></i>
                                </button>
                                <span class="font-bold text-sm text-slate-800 quantity-val">{{ $details['quantity'] }}</span>
                                <button type="button" onclick="updateQty('{{ $cartKey }}', 1)" class="h-8 w-8 flex items-center justify-center rounded-lg hover:bg-slate-200 text-slate-600 transition-colors">
                                    <i class="fa-solid fa-plus text-[10px]"></i>
                                </button>
                            </div>

                            <!-- Line total -->
                            <span class="font-extrabold text-slate-900 text-base min-w-[70px] text-right">
                                ৳{{ number_format($details['price'] * $details['quantity'], 0) }}
                            </span>

                            <!-- Remove -->
                            <button onclick="removeCartItem('{{ $cartKey }}')" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2.5 rounded-xl transition-colors">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Order Summary Card -->
            <div class="bg-white border border-slate-200/80 rounded-[32px] p-6 sm:p-8 space-y-6 shadow-sm">
                <h3 class="font-extrabold text-slate-900 text-xl border-b border-slate-100 pb-4">Order Summary</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between text-sm font-semibold text-slate-600">
                        <span>Subtotal</span>
                        <span class="text-slate-900 font-bold cart-subtotal">৳{{ number_format($total, 0) }}</span>
                    </div>
                    <div class="flex justify-between text-sm font-semibold text-slate-600">
                        <span>Shipping Fee</span>
                        <span class="text-slate-900 font-bold">৳{{ number_format($shipping, 0) }}</span>
                    </div>
                    <div class="border-t border-slate-100 pt-4 flex justify-between font-extrabold text-slate-900 text-lg">
                        <span>Total</span>
                        <span class="text-primary-600 cart-grandtotal">৳{{ number_format($grandTotal, 0) }}</span>
                    </div>
                </div>

                <div class="pt-4">
                    <a href="{{ route('checkout.index') }}" class="w-full py-4 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-2xl shadow-lg shadow-primary-600/30 transition-all text-center flex items-center justify-center gap-2">
                        Checkout <i class="fa-solid fa-arrow-right text-sm"></i>
                    </a>
                    <a href="{{ route('products.index') }}" class="w-full mt-3 py-3 border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold rounded-2xl transition-all text-center block text-sm">
                        Continue Shopping
                    </a>
                </div>
            </div>

        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    function updateQty(cartKey, change) {
        const itemCard = document.querySelector(`[data-cart-key="${cartKey}"]`);
        const qtySpan  = itemCard.querySelector('.quantity-val');
        let currentQty = parseInt(qtySpan.innerText);
        let newQty     = currentQty + change;

        if (newQty < 1) return;

        fetch("{{ route('cart.update') }}", {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ cart_key: cartKey, quantity: newQty })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                qtySpan.innerText = newQty;
                updateCartTotals();

                Swal.fire({
                    icon: 'success',
                    title: 'Cart Updated!',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#0ea5e9',
                    customClass: { popup: 'rounded-2xl shadow-2xl border border-green-200' }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message || 'Failed to update cart',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc2626',
                    customClass: { popup: 'rounded-2xl shadow-2xl border border-red-200' }
                });
            }
        });
    }

    async function removeCartItem(cartKey) {
        const result = await Swal.fire({
            title: 'Remove Item?',
            text: 'Are you sure you want to remove this item from your cart?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, remove it!',
            cancelButtonText: 'Cancel',
            customClass: { popup: 'rounded-2xl shadow-2xl border border-slate-200' }
        });

        if (!result.isConfirmed) return;

        const itemCard = document.querySelector(`[data-cart-key="${cartKey}"]`);
        itemCard.style.opacity = '0.3';

        fetch("{{ route('cart.remove') }}", {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ cart_key: cartKey })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                itemCard.remove();
                updateCartTotals();

                const remaining = document.querySelectorAll('[data-cart-key]');
                if (remaining.length === 0) {
                    location.reload();
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Item Removed!',
                        text: 'The item has been removed from your cart.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#0ea5e9',
                        customClass: { popup: 'rounded-2xl shadow-2xl border border-green-200' }
                    });
                }
            }
        });
    }

    function updateCartTotals() {
        const subtotalEl = document.querySelector('.cart-subtotal');
        const grandTotalEl = document.querySelector('.cart-grandtotal');
        if (!subtotalEl && !grandTotalEl) return;

        let subtotal = 0;
        document.querySelectorAll('[data-cart-key]').forEach(card => {
            const priceText = card.querySelector('.font-extrabold.text-slate-900.text-base')?.innerText;
            if (priceText) {
                subtotal += parseFloat(priceText.replace('৳', '').replace(/,/g, ''));
            }
        });

        const shipping = {{ $shipping }};
        const grandTotal = subtotal + shipping;

        if (subtotalEl) subtotalEl.innerText = '৳' + subtotal.toLocaleString();
        if (grandTotalEl) grandTotalEl.innerText = '৳' + grandTotal.toLocaleString();
    }
</script>
@endsection
