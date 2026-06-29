@extends('layouts.shop')

@section('title', 'Checkout')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 mb-8 sm:mb-10" data-aos="fade-up">
        <div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Checkout</h1>
            <p class="text-sm text-slate-500 mt-1">Complete your order by filling in the details below.</p>
        </div>
        <a href="{{ route('cart.index') }}" class="group inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-primary-600 bg-white hover:bg-primary-50 px-4 py-2 rounded-xl border border-slate-200 hover:border-primary-200 transition-all shrink-0 shadow-sm">
            <i class="fa-solid fa-chevron-left text-[10px] group-hover:-translate-x-0.5 transition-transform"></i>
            Back to Cart
        </a>
    </div>

    <form action="{{ route('checkout.place') }}" method="POST" id="checkout-form">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            {{-- ============= LEFT COLUMN: Billing + Product Details ============= --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- ======= Billing Details ======= --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-8 shadow-sm" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-primary-600 flex items-center justify-center shadow-md">
                            <i class="fa-solid fa-user-pen text-white text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Billing Details</h2>
                            <p class="text-xs text-slate-500">Fill in your information to complete the order</p>
                        </div>
                    </div>

                    <div class="space-y-5">

                        {{-- Name --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Enter Your Name <span class="text-red-500">*</span></label>
                            <input type="text" name="customer_name" value="{{ old('customer_name', Auth::check() ? Auth::user()->name : '') }}" required
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all placeholder:text-slate-400"
                                placeholder="Write your full name">
                            @error('customer_name')
                                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email (Optional) --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Email Address <span class="text-slate-400 font-normal">(Optional)</span></label>
                            <input type="email" name="customer_email" value="{{ old('customer_email', Auth::check() ? Auth::user()->email : '') }}"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all placeholder:text-slate-400"
                                placeholder="you@example.com">
                            @error('customer_email')
                                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Mobile Number --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Enter your mobile number <span class="text-red-500">*</span></label>
                            <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" required maxlength="11"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all placeholder:text-slate-400"
                                placeholder="Enter your 11-digit mobile number">
                            @error('customer_phone')
                                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Select Area --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Select Area <span class="text-red-500">*</span></label>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="radio" name="area" value="inside_dhaka" {{ old('area', 'inside_dhaka') === 'inside_dhaka' ? 'checked' : '' }} required
                                        class="w-4 h-4 text-primary-600 border-slate-300 focus:ring-primary-500">
                                    <span class="text-sm font-semibold text-slate-700 group-hover:text-primary-600 transition-colors">Inside Dhaka</span>
                                </label>
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="radio" name="area" value="outside_dhaka" {{ old('area') === 'outside_dhaka' ? 'checked' : '' }}
                                        class="w-4 h-4 text-primary-600 border-slate-300 focus:ring-primary-500">
                                    <span class="text-sm font-semibold text-slate-700 group-hover:text-primary-600 transition-colors">Outside Dhaka</span>
                                </label>
                            </div>
                            @error('area')
                                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Full Address --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Full Address <span class="text-red-500">*</span></label>
                            <textarea name="shipping_address" rows="3" required
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all placeholder:text-slate-400 resize-none"
                                placeholder="House number, road, unit/flat, postal code, district">{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Order Notes --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Order Notes <span class="text-slate-400 font-normal">(Optional)</span></label>
                            <textarea name="notes" rows="2"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all placeholder:text-slate-400 resize-none"
                                placeholder="Special delivery instructions, gate codes, etc.">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Payment Method --}}
                        <div class="p-4 bg-primary-50 border border-primary-100 rounded-xl flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-primary-600 flex items-center justify-center shadow-md shrink-0">
                                <i class="fa-solid fa-money-bill-wave text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-900">Cash on Delivery (COD)</p>
                                <p class="text-xs text-slate-500">Pay when you receive your order</p>
                            </div>
                            <span class="text-[10px] font-bold bg-primary-100 text-primary-700 px-2 py-1 rounded-lg ml-auto">No extra fees</span>
                        </div>

                    </div>
                </div>

                {{-- ======= Product Details ======= --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-8 shadow-sm" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-primary-600 flex items-center justify-center shadow-md">
                            <i class="fa-solid fa-bag-shopping text-white text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Product Details</h2>
                            <p class="text-xs text-slate-500">{{ count($cart) }} {{ Str::plural('item', count($cart)) }} in your order</p>
                        </div>
                    </div>

                    {{-- Desktop Table Header --}}
                    <div class="hidden sm:grid grid-cols-12 gap-4 pb-3 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <div class="col-span-6">Product Name</div>
                        <div class="col-span-3 text-center">Selling Price</div>
                        <div class="col-span-3 text-center">Image</div>
                    </div>

                    {{-- Cart Items --}}
                    <div id="checkout-items">
                        @foreach($cart as $cartKey => $details)
                            <div class="sm:grid sm:grid-cols-12 sm:gap-4 items-center py-5 border-b border-slate-100 last:border-0 gap-4 group" data-cart-key="{{ $cartKey }}">

                                {{-- Product Name + Color --}}
                                <div class="sm:col-span-6">
                                    <div class="flex items-center gap-3">
                                        {{-- Mobile-only image --}}
                                        <div class="h-14 w-14 rounded-lg overflow-hidden bg-slate-100 border border-slate-200 shrink-0 sm:hidden">
                                            <img src="{{ $details['image'] }}" alt="{{ $details['name'] }}" class="h-full w-full object-cover">
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-slate-900 text-sm leading-tight">{{ $details['name'] }}</h3>
                                            @if(!empty($details['selected_color']))
                                                <p class="text-xs text-slate-500 mt-0.5">Color : {{ $details['selected_color'] }}</p>
                                            @endif
                                            @if(!empty($details['selected_size']))
                                                <p class="text-xs text-slate-500 mt-0.5">Size : {{ $details['selected_size'] }}</p>
                                            @endif
                                            <p class="text-xs text-slate-400 mt-1.5 sm:hidden">
                                                Qty: {{ $details['quantity'] }} &times; ৳{{ number_format($details['price'], 0) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Selling Price --}}
                                <div class="sm:col-span-3 flex sm:justify-center items-center gap-2">
                                    <span class="font-extrabold text-primary-700 text-base">৳{{ number_format($details['price'], 0) }}</span>
                                    @if(!empty($details['compare_price']) && $details['compare_price'] > $details['price'])
                                        <span class="text-xs text-slate-400 line-through">৳{{ number_format($details['compare_price'], 0) }}</span>
                                    @endif
                                </div>

                                {{-- Image (desktop) --}}
                                <div class="hidden sm:flex sm:col-span-3 justify-center">
                                    <div class="h-16 w-16 rounded-lg overflow-hidden bg-slate-100 border border-slate-200 shadow-sm group-hover:shadow-md transition-shadow">
                                        <img src="{{ $details['image'] }}" alt="{{ $details['name'] }}" class="h-full w-full object-cover">
                                    </div>
                                </div>

                                {{-- Quantity + Remove (below on mobile, right-aligned on desktop) --}}
                                <div class="sm:col-span-12 flex items-center justify-between sm:justify-end gap-4 pt-2 sm:pt-0 border-t border-slate-50 sm:border-0">
                                    {{-- Quantity selector --}}
                                    <div class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-slate-50">
                                        <button type="button" onclick="updateCheckoutQty('{{ $cartKey }}', -1)"
                                            class="h-8 w-8 flex items-center justify-center hover:bg-slate-200 text-slate-600 transition-colors text-xs">
                                            <i class="fa-solid fa-minus text-[10px]"></i>
                                        </button>
                                        <span class="h-8 w-8 flex items-center justify-center font-bold text-sm text-slate-800 quantity-val">{{ $details['quantity'] }}</span>
                                        <button type="button" onclick="updateCheckoutQty('{{ $cartKey }}', 1)"
                                            class="h-8 w-8 flex items-center justify-center hover:bg-slate-200 text-slate-600 transition-colors text-xs">
                                            <i class="fa-solid fa-plus text-[10px]"></i>
                                        </button>
                                    </div>

                                    {{-- Remove --}}
                                    <button type="button" onclick="removeCheckoutItem('{{ $cartKey }}')"
                                        class="text-slate-400 hover:text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors text-xs font-semibold flex items-center gap-1.5">
                                        <i class="fa-solid fa-trash-can text-[10px]"></i>
                                        <span class="hidden sm:inline">Remove</span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if(empty($cart))
                        <div class="py-12 text-center">
                            <i class="fa-solid fa-basket-shopping text-3xl text-slate-300 mb-3"></i>
                            <p class="text-slate-500 text-sm">Your cart is empty.</p>
                        </div>
                    @endif

                </div>
            </div>

            {{-- ============= RIGHT COLUMN: Order Summary ============= --}}
            <div data-aos="fade-up" data-aos-delay="300">
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm sticky top-24">
                    <h3 class="font-extrabold text-slate-900 text-lg border-b border-slate-100 pb-4 mb-4">Order Summary</h3>

                    {{-- Summary Items --}}
                    <div class="space-y-3 max-h-[200px] overflow-y-auto pr-1">
                        @foreach($cart as $cartKey => $details)
                            <div class="flex items-center justify-between gap-3 text-sm" data-cart-key="{{ $cartKey }}">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="h-10 w-10 rounded-lg overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                        <img src="{{ $details['image'] }}" alt="{{ $details['name'] }}" class="h-full w-full object-cover">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-slate-700 text-xs truncate">{{ $details['name'] }}</p>
                                        <p class="text-[10px] text-slate-400">Qty: <span class="summary-qty">{{ $details['quantity'] }}</span></p>
                                    </div>
                                </div>
                                <span class="font-bold text-slate-800 text-sm shrink-0 summary-line-total">৳{{ number_format($details['price'] * $details['quantity'], 0) }}</span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Totals --}}
                    <div class="mt-5 pt-4 border-t border-slate-100 space-y-3">
                        <div class="flex justify-between text-sm font-semibold text-slate-500">
                            <span>Sub-Total (+)</span>
                            <span class="text-slate-800" id="checkout-subtotal">৳{{ number_format($total, 0) }}</span>
                        </div>
                        <div class="flex justify-between text-sm font-semibold text-slate-500">
                            <span>Shipping</span>
                            <span class="text-slate-800" id="checkout-shipping">৳{{ number_format($shipping, 0) }}</span>
                        </div>
                        <div class="border-t border-slate-200 pt-3 flex justify-between items-center">
                            <span class="font-extrabold text-slate-900 text-base">Total</span>
                            <span class="text-xl font-extrabold text-primary-600" id="checkout-grandtotal">৳{{ number_format($grandTotal, 0) }}</span>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="w-full mt-6 py-4 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl shadow-lg shadow-primary-600/30 hover:shadow-primary-600/50 transition-all duration-300 flex items-center justify-center gap-2.5 hover:-translate-y-0.5 active:scale-[0.98] text-sm">
                        <i class="fa-solid fa-lock text-xs"></i>
                        Place Order (COD)
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>

                    {{-- Trust --}}
                    <div class="mt-4 flex items-center gap-2 text-[10px] text-slate-500 justify-center">
                        <i class="fa-solid fa-shield-check text-primary-500"></i>
                        <span>Your information is secured via <strong class="text-slate-700">SSL encryption</strong></span>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const shippingInsideDhaka = {{ $shippingInsideDhaka }};
    const shippingOutsideDhaka = {{ $shippingOutsideDhaka }};

    // --- Quantity Update ---
    function updateCheckoutQty(cartKey, change) {
        const itemCard = document.querySelector(`#checkout-items [data-cart-key="${cartKey}"]`);
        if (!itemCard) return;
        const qtySpan = itemCard.querySelector('.quantity-val');
        let currentQty = parseInt(qtySpan.innerText);
        let newQty = currentQty + change;

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
                updateCheckoutTotals();
            } else {
                Swal.fire({
                    icon: 'error', title: 'Error!', text: data.message || 'Failed to update',
                    confirmButtonText: 'OK', confirmButtonColor: '#dc2626',
                    background: '#fef2f2', color: '#991b1b', iconColor: '#dc2626',
                    customClass: { popup: 'rounded-2xl shadow-2xl border border-red-200' }
                });
            }
        });
    }

    // --- Remove Item ---
    async function removeCheckoutItem(cartKey) {
        const result = await Swal.fire({
            title: 'Remove Item?',
            text: 'Are you sure you want to remove this item from your order?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, remove it!',
            cancelButtonText: 'Cancel',
            customClass: { popup: 'rounded-2xl shadow-2xl border border-slate-200' }
        });

        if (!result.isConfirmed) return;

        const itemCards = document.querySelectorAll(`[data-cart-key="${cartKey}"]`);
        itemCards.forEach(c => c.style.opacity = '0.3');

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
                itemCards.forEach(c => c.remove());
                updateCheckoutTotals();
                const remaining = document.querySelectorAll('#checkout-items [data-cart-key]');
                if (remaining.length === 0) {
                    location.reload();
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Item Removed!',
                        text: 'The item has been removed from your order.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#0ea5e9',
                        customClass: { popup: 'rounded-2xl shadow-2xl border border-green-200' }
                    });
                }
            }
        });
    }

    // --- Recalculate Totals ---
    function updateCheckoutTotals() {
        let subtotal = 0;
        const checkoutItems = document.querySelectorAll('#checkout-items [data-cart-key]');

        checkoutItems.forEach(card => {
            const qtySpan = card.querySelector('.quantity-val');
            const priceText = card.querySelector('.font-extrabold.text-primary-700')?.innerText;
            if (qtySpan && priceText) {
                const price = parseFloat(priceText.replace('৳', '').replace(',', ''));
                const qty = parseInt(qtySpan.innerText);
                subtotal += price * qty;
            }
        });

        // Update summary items
        const summaryCards = document.querySelectorAll('.sticky [data-cart-key]');
        summaryCards.forEach(sc => {
            const cartKey = sc.getAttribute('data-cart-key');
            const itemCard = document.querySelector(`#checkout-items [data-cart-key="${cartKey}"]`);
            if (itemCard) {
                const qtySpan = itemCard.querySelector('.quantity-val');
                const priceText = itemCard.querySelector('.font-extrabold.text-primary-700')?.innerText;
                if (qtySpan && priceText) {
                    const price = parseFloat(priceText.replace('৳', '').replace(',', ''));
                    const qty = parseInt(qtySpan.innerText);
                    sc.querySelector('.summary-qty').innerText = qty;
                    sc.querySelector('.summary-line-total').innerText = '৳' + (price * qty).toLocaleString();
                }
            }
        });

        // Determine shipping based on area
        const areaRadio = document.querySelector('input[name="area"]:checked');
        const shipping = (areaRadio && areaRadio.value === 'outside_dhaka') ? shippingOutsideDhaka : shippingInsideDhaka;
        const grandTotal = subtotal + shipping;

        document.getElementById('checkout-subtotal').innerText = '৳' + subtotal.toLocaleString();
        document.getElementById('checkout-shipping').innerText = '৳' + shipping.toLocaleString();
        document.getElementById('checkout-grandtotal').innerText = '৳' + grandTotal.toLocaleString();
    }

    // --- Area radio change updates shipping ---
    document.querySelectorAll('input[name="area"]').forEach(radio => {
        radio.addEventListener('change', updateCheckoutTotals);
    });

    // --- Mobile number validation (digits only, max 11) ---
    document.querySelector('input[name="customer_phone"]')?.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '').substring(0, 11);
    });
</script>
@endsection

@push('styles')
<style>
    input:-webkit-autofill,
    input:-webkit-autofill:focus {
        -webkit-box-shadow: 0 0 0 30px #fff inset !important;
        -webkit-text-fill-color: #1e293b !important;
    }
</style>
@endpush
