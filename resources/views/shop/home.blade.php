@extends('layouts.shop')

@section('title', 'Welcome to ' . \App\Models\Setting::get('site_name', 'TechEx'))

@section('content')

{{-- ============================================ --}}
{{-- 1. HERO SLIDER --}}
{{-- ============================================ --}}
@if($sliders->count() > 0)
<div class="swiper hero-slider w-full h-[55vh] min-h-[400px] max-h-[750px] lg:min-h-[550px] lg:max-h-[880px]" data-aos="fade" data-aos-duration="1000">
    <div class="swiper-wrapper">
        @foreach($sliders as $index => $slide)
            <div class="swiper-slide relative overflow-hidden bg-slate-900">
                <img src="{{ $slide->image_url }}" alt="Slide {{ $index + 1 }}"
                     class="h-full w-full object-cover object-center swiper-slide-zoom">

                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 via-50% to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-black/40 via-transparent to-black/20"></div>

                <div class="absolute top-20 right-20 w-64 h-64 bg-primary-500/10 rounded-full blur-3xl hidden lg:block"></div>
                <div class="absolute bottom-40 left-20 w-48 h-48 bg-white/5 rounded-full blur-2xl hidden lg:block"></div>

                <div class="absolute inset-0 z-10 flex items-center pb-12 sm:pb-16 lg:pb-20">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                        <div class="max-w-xl" data-aos="fade-up" data-aos-delay="200">
                            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-[10px] sm:text-xs font-semibold bg-white/15 text-white backdrop-blur-md border border-white/10 mb-4 sm:mb-5 shadow-lg">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                New Collection
                            </span>
                            <h2 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-[1.1] mb-3 sm:mb-4 drop-shadow-2xl tracking-tight">
                                Premium Tech Gear
                            </h2>
                            <p class="text-sm sm:text-base lg:text-lg text-white/65 max-w-lg mb-5 sm:mb-6 leading-relaxed drop-shadow-lg">
                                Discover cutting-edge electronics and accessories designed for modern creators and innovators.
                            </p>
                            @if($slide->link)
                                <a href="{{ $slide->link }}"
                                   class="group inline-flex items-center gap-2.5 px-6 sm:px-8 py-3 sm:py-4 bg-white text-slate-900 font-bold text-sm sm:text-base rounded-2xl hover:bg-primary-600 hover:text-white transition-all duration-300 shadow-2xl shadow-black/30 hover:shadow-primary-600/40 hover:-translate-y-1">
                                    Shop Now
                                    <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="swiper-pagination !bottom-4 sm:!bottom-6"></div>

    <div class="hero-btn-prev !w-11 !h-11 sm:!w-12 sm:!h-12 !rounded-full !bg-white/10 hover:!bg-white/25 !backdrop-blur-md transition-all !hidden sm:!flex">
        <i class="fa-solid fa-chevron-left text-white text-base sm:text-lg drop-shadow"></i>
    </div>
    <div class="hero-btn-next !w-11 !h-11 sm:!w-12 sm:!h-12 !rounded-full !bg-white/10 hover:!bg-white/25 !backdrop-blur-md transition-all !hidden sm:!flex">
        <i class="fa-solid fa-chevron-right text-white text-base sm:text-lg drop-shadow"></i>
    </div>
</div>
@else
<div class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white py-20 sm:py-24" data-aos="fade">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-primary-600/20 via-transparent to-transparent"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-primary-500/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-white/5 rounded-full blur-2xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-2xl" data-aos="fade-up" data-aos-delay="100">
            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-semibold bg-primary-500/20 text-primary-300 border border-primary-500/20 mb-5 shadow-lg">
                <span class="w-1.5 h-1.5 rounded-full bg-primary-400 animate-pulse"></span>
                High-Quality Premium Gear
            </span>
            <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight mb-5 leading-[1.05]">
                Elevate Your Tech
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-blue-400">&amp; Lifestyle.</span>
            </h1>
            <p class="text-lg sm:text-xl text-slate-300 mb-8 leading-relaxed max-w-xl">
                Discover our curated collection of high-performance wireless audio, minimalist accessories, and premium stationery designed for modern creators.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('products.index') }}"
                   class="group px-8 py-4 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-2xl shadow-xl shadow-primary-600/30 transition-all hover:-translate-y-1 hover:shadow-primary-600/50 flex items-center gap-2.5">
                    Shop Now <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform text-sm"></i>
                </a>
                <a href="{{ route('orders.track') }}"
                   class="px-8 py-4 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-2xl transition-all border border-white/10 hover:border-white/20 backdrop-blur-sm flex items-center gap-2.5">
                    <i class="fa-regular fa-compass"></i> Track Order
                </a>
            </div>
        </div>
    </div>
</div>
@endif


{{-- ============================================ --}}
{{-- 2. TRUST BADGES / WHY CHOOSE US --}}
{{-- ============================================ --}}
<div class="relative -mt-12 sm:-mt-16 z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" data-aos="fade-up" data-aos-delay="300">
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
        @php
            $benefits = [
                ['icon' => 'fa-shield-check', 'title' => 'Secure Payment', 'desc' => '100% protected', 'color' => 'from-emerald-600 to-teal-500'],
                ['icon' => 'fa-rotate-left', 'title' => 'Easy Returns', 'desc' => '30-day return policy', 'color' => 'from-orange-600 to-amber-500'],
                ['icon' => 'fa-headset', 'title' => '24/7 Support', 'desc' => 'Dedicated help center', 'color' => 'from-purple-600 to-pink-500'],
            ];
        @endphp
        @foreach($benefits as $b)
            <div class="group bg-slate-800/80 backdrop-blur-sm rounded-2xl p-4 shadow-xl shadow-black/30 border border-white/15 hover:border-white/30 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $b['color'] }} flex items-center justify-center shadow-lg mb-3 group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid {{ $b['icon'] }} text-white text-sm"></i>
                </div>
                <h4 class="font-bold text-white text-sm sm:text-base">{{ $b['title'] }}</h4>
                <p class="text-xs sm:text-sm text-slate-400 mt-0.5">{{ $b['desc'] }}</p>
            </div>
        @endforeach
    </div>
</div>


{{-- ============================================ --}}
{{-- 3. STATS COUNTER SECTION --}}
{{-- ============================================ --}}
<div class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 mt-12 sm:mt-16 py-14 sm:py-16 relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-primary-600/10 via-transparent to-transparent"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 sm:gap-10">
            @php
                $stats = [
                    ['value' => $totalProducts, 'label' => 'Products', 'icon' => 'fa-box', 'suffix' => '+'],
                    ['value' => $totalOrders, 'label' => 'Orders Delivered', 'icon' => 'fa-truck', 'suffix' => '+'],
                    ['value' => $totalReviews, 'label' => 'Happy Reviews', 'icon' => 'fa-star', 'suffix' => '+'],
                    ['value' => $totalCategories, 'label' => 'Categories', 'icon' => 'fa-layer-group', 'suffix' => '+'],
                ];
            @endphp
            @foreach($stats as $s)
                <div class="text-center" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-sm flex items-center justify-center mx-auto mb-3 border border-white/10 shadow-lg">
                        <i class="fa-solid {{ $s['icon'] }} text-primary-400 text-xl sm:text-2xl"></i>
                    </div>
                    <div class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white mb-1 tracking-tight counter-value" data-target="{{ $s['value'] }}" data-suffix="{{ $s['suffix'] }}">0{{ $s['suffix'] }}</div>
                    <p class="text-sm sm:text-base text-slate-400 font-medium">{{ $s['label'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>


{{-- ============================================ --}}
{{-- 4. CATEGORIES SECTION --}}
{{-- ============================================ --}}
<div class="bg-slate-200/60 py-14 sm:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10 sm:mb-12" data-aos="fade-up">
            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-semibold bg-slate-800 text-slate-200 mb-3">
                <i class="fa-solid fa-grid-2"></i> Categories
            </span>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight mb-2">Shop by Category</h2>
            <p class="text-sm sm:text-base text-slate-600 max-w-xl mx-auto">Explore our curated collections tailored to your style.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            @foreach($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                   class="group relative rounded-2xl overflow-hidden bg-slate-50 border border-slate-300/60 hover:border-primary-300/60 transition-all duration-300 hover:shadow-xl hover:shadow-primary-500/10 hover:-translate-y-1"
                   data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">

                    @php
                        $gradients = [
                            'from-blue-600 to-indigo-600',
                            'from-emerald-500 to-teal-600',
                            'from-orange-500 to-rose-600',
                            'from-purple-600 to-pink-600',
                            'from-cyan-500 to-blue-600',
                            'from-amber-500 to-orange-600',
                        ];
                        $gradient = $gradients[$loop->index % count($gradients)];
                    @endphp
                    <div class="h-28 sm:h-32 bg-gradient-to-br {{ $gradient }} flex items-center justify-center relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                        <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
                        <i class="fa-solid fa-{{ $category->slug == 'electronics' ? 'laptop' : ($category->slug == 'fashion' ? 'shirt' : ($category->slug == 'home-living' ? 'couch' : 'pen-nib')) }} text-white text-3xl sm:text-4xl opacity-80 group-hover:scale-110 transition-transform duration-500"></i>
                    </div>

                    <div class="p-4 sm:p-5">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="font-bold text-slate-800 group-hover:text-primary-600 transition-colors text-sm sm:text-base">{{ $category->name }}</h3>
                            <span class="text-[10px] font-semibold text-slate-500 bg-slate-200 px-2 py-0.5 rounded-full whitespace-nowrap">
                                {{ $category->products_count }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 line-clamp-1">{{ $category->description ?? 'Explore our collection' }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        @if($categories->count() > 4)
        <div class="text-center mt-8" data-aos="fade-up">
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-white font-bold text-sm rounded-xl transition-all hover:-translate-y-0.5 shadow-lg">
                View All Categories <i class="fa-solid fa-chevron-right text-[10px]"></i>
            </a>
        </div>
        @endif
    </div>
</div>


{{-- ============================================ --}}
{{-- 5. FEATURED PRODUCTS --}}
{{-- ============================================ --}}
<div class="bg-gradient-to-b from-slate-100 to-slate-200/80 py-14 sm:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 sm:mb-10 gap-3" data-aos="fade-up">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-700 border border-amber-200 mb-3">
                    <i class="fa-solid fa-star"></i> Featured
                </span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight mb-1">Trending Now</h2>
                <p class="text-sm sm:text-base text-slate-600">Our most popular products handpicked for you.</p>
            </div>
            <a href="{{ route('products.index') }}"
               class="group inline-flex items-center gap-1.5 text-xs font-bold text-slate-700 hover:text-primary-600 bg-slate-200/80 hover:bg-primary-50 px-4 py-2 rounded-xl transition-all shrink-0">
                View All Catalog <i class="fa-solid fa-chevron-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
            @forelse($featuredProducts as $product)
                <div class="group relative bg-slate-50 border border-slate-200/80 rounded-2xl overflow-hidden hover:shadow-xl hover:shadow-slate-300/50 hover:-translate-y-1 transition-all duration-300 flex flex-col cursor-pointer"
                     onclick="window.location='{{ route('products.show', $product->slug) }}'"
                     data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">

                    <div class="aspect-[4/3] w-full overflow-hidden bg-slate-200 relative">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                             class="h-full w-full object-cover object-center group-hover:scale-110 transition-transform duration-700 ease-out">

                        @if($product->total_stock <= 0)
                            <span class="absolute top-3 left-3 bg-red-600/90 backdrop-blur-sm text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider shadow-lg">Out of Stock</span>
                        @elseif(!empty($product->sizes) || !empty($product->colors))
                            <span class="absolute top-3 left-3 bg-blue-500/90 backdrop-blur-sm text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider shadow-lg">Variants</span>
                        @elseif($product->stock <= 5)
                            <span class="absolute top-3 left-3 bg-orange-500/90 backdrop-blur-sm text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider shadow-lg">Low Stock</span>
                        @endif

                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all duration-300 flex items-center justify-center">
                            <span class="opacity-0 group-hover:opacity-100 -translate-y-2 group-hover:translate-y-0 transition-all duration-300 bg-white/90 backdrop-blur-sm text-slate-800 text-[10px] font-bold px-3.5 py-1.5 rounded-full shadow-xl flex items-center gap-1.5">
                                <i class="fa-regular fa-eye"></i> Quick View
                            </span>
                        </div>
                    </div>

                    <div class="p-4 sm:p-5 flex-grow flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <span class="text-[9px] font-bold uppercase tracking-[0.12em] text-primary-600 bg-primary-50 px-2 py-0.5 rounded-md">{{ $product->category->name }}</span>
                            </div>
                            <h3 class="font-bold text-slate-800 group-hover:text-primary-600 transition-colors text-base mb-1.5 leading-snug">{{ $product->name }}</h3>
                            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">{{ $product->description }}</p>
                        </div>

                        <div class="flex items-center justify-between pt-3 mt-3 border-t border-slate-200">
                            <div>
                                <span class="text-lg sm:text-xl font-extrabold text-slate-800">৳{{ number_format($product->price, 0) }}</span>
                                @if($product->compare_price)
                                    <span class="text-xs text-slate-400 line-through ml-1.5">৳{{ number_format($product->compare_price, 0) }}</span>
                                @endif
                            </div>

                            @if(!empty($product->sizes) || !empty($product->colors))
                                <a href="{{ route('products.show', $product->slug) }}" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold rounded-xl transition-all duration-200 flex items-center gap-1.5 hover:shadow-lg hover:shadow-blue-600/30 active:scale-95" onclick="event.stopPropagation()">
                                    <i class="fa-solid fa-list text-xs"></i>
                                    <span class="hidden sm:inline">Options</span>
                                </a>
                            @elseif($product->stock > 0)
                                <form action="{{ route('cart.add', $product->id) }}" method="POST" onclick="event.stopPropagation()">
                                    @csrf
                                    <button type="submit"
                                            class="px-3.5 py-2 bg-slate-800 hover:bg-primary-600 text-white text-[10px] font-bold rounded-xl transition-all duration-200 flex items-center gap-1.5 hover:shadow-lg hover:shadow-primary-600/30 active:scale-95">
                                        <i class="fa-solid fa-cart-plus text-xs"></i>
                                        <span class="hidden sm:inline">Add</span>
                                    </button>
                                </form>
                            @else
                                <button class="px-3.5 py-2 bg-slate-200 text-slate-400 text-[10px] font-bold rounded-xl cursor-not-allowed flex items-center gap-1.5" disabled>
                                    <i class="fa-regular fa-circle-xmark"></i> Unavailable
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 sm:py-16" data-aos="fade-up">
                    <div class="w-16 h-16 rounded-2xl bg-slate-200 flex items-center justify-center mx-auto mb-4">
                        <i class="fa-regular fa-box text-2xl text-slate-500"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700 mb-1">No Featured Products Yet</h3>
                    <p class="text-sm text-slate-500 mb-5">Check back soon for our curated picks.</p>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-primary-600 text-white rounded-xl font-bold text-sm hover:bg-primary-700 transition-all">
                        Browse All Products <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>





{{-- ============================================ --}}
{{-- 7. NEWSLETTER / CTA BANNER --}}
{{-- ============================================ --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16" data-aos="fade-up">
    <div class="relative overflow-hidden rounded-2xl sm:rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 px-6 sm:px-10 lg:px-14 py-10 sm:py-14 shadow-2xl">
        <div class="absolute top-0 right-0 w-72 h-72 bg-primary-600/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-56 h-56 bg-blue-600/10 rounded-full blur-3xl translate-y-1/3 -translate-x-1/4"></div>

        <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-6">
            <div class="text-center lg:text-left max-w-xl">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-semibold bg-white/10 text-white border border-white/10 backdrop-blur-sm mb-3">
                    <i class="fa-regular fa-envelope"></i> Stay Connected
                </span>
                <h3 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight mb-2">Join Our Newsletter</h3>
                <p class="text-sm sm:text-base text-slate-400 leading-relaxed">Get special offers, new arrivals, and exclusive deals straight to your inbox.</p>
            </div>

            <div class="w-full lg:w-auto lg:min-w-[380px]">
                <form class="flex flex-col sm:flex-row gap-2.5" onsubmit="event.preventDefault(); alert('Newsletter subscription coming soon!');">
                    <div class="flex-1 relative">
                        <i class="fa-regular fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                        <input type="email" placeholder="Enter your email" required
                               class="w-full pl-10 pr-3.5 py-3 rounded-xl bg-white/10 backdrop-blur-sm border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm transition-all">
                    </div>
                    <button type="submit"
                            class="px-5 sm:px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl transition-all hover:-translate-y-0.5 hover:shadow-xl hover:shadow-primary-600/30 active:scale-95 flex items-center gap-1.5 shrink-0 text-sm whitespace-nowrap">
                        Subscribe <i class="fa-solid fa-paper-plane text-[10px]"></i>
                    </button>
                </form>
                <p class="text-[10px] text-slate-500 mt-2 text-center lg:text-left">No spam, ever. Unsubscribe anytime.</p>
            </div>
        </div>
    </div>
</div>

@endsection


@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
<style>
.hero-slider .swiper-pagination-bullet {
    width: 10px;
    height: 10px;
    background: rgba(255,255,255,0.4);
    opacity: 1;
    transition: all 0.3s ease;
}
.hero-slider .swiper-pagination-bullet-active {
    background: white;
    width: 32px;
    border-radius: 5px;
}
.hero-slider:hover .hero-btn-prev,
.hero-slider:hover .hero-btn-next {
    opacity: 0.9 !important;
}
.hero-btn-prev,
.hero-btn-next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
}
.hero-btn-prev { left: 1rem; }
.hero-btn-next { right: 1rem; }
@media (min-width: 640px) {
    .hero-btn-prev { left: 1.5rem; }
    .hero-btn-next { right: 1.5rem; }
}
.hero-slider .swiper-slide-active .swiper-slide-zoom {
    animation: hero-zoom 8s ease-in-out forwards;
}
@keyframes hero-zoom {
    0% { transform: scale(1); }
    100% { transform: scale(1.05); }
}

.testimonials-slider .swiper-wrapper { padding-bottom: 0.5rem; }
.testimonial-pagination .swiper-pagination-bullet {
    width: 10px;
    height: 10px;
    background: rgba(255,255,255,0.3);
    opacity: 1;
    transition: all 0.3s ease;
}
.testimonial-pagination .swiper-pagination-bullet-active {
    background: white;
    width: 28px;
    border-radius: 5px;
}

body {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

::-webkit-scrollbar { width: 8px; }
::-webkit-scrollbar-track { background: #e2e8f0; }
::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 4px; }
::-webkit-scrollbar-thumb:hover { background: #64748b; }

.counter-value { font-variant-numeric: tabular-nums; }
</style>
@endpush


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    new Swiper('.hero-slider', {
        loop: true,
        autoplay: { delay: 5500, disableOnInteraction: false },
        speed: 900,
        effect: 'fade',
        fadeEffect: { crossFade: true },
        pagination: { el: '.swiper-pagination', clickable: true },
        navigation: { nextEl: '.hero-btn-next', prevEl: '.hero-btn-prev' },
    });

    if (document.querySelector('.testimonials-slider')) {
        new Swiper('.testimonials-slider', {
            loop: true,
            autoplay: { delay: 4000, disableOnInteraction: false },
            speed: 600,
            slidesPerView: 1,
            spaceBetween: 20,
            pagination: { el: '.testimonial-pagination', clickable: true },
            breakpoints: { 640: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } },
        });
    }

    const counters = document.querySelectorAll('.counter-value');
    counters.forEach(counter => {
        const target = parseInt(counter.dataset.target);
        const suffix = counter.dataset.suffix || '';
        const duration = 1500;
        const steps = 60;
        const increment = target / steps;
        let current = 0;
        let step = 0;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const timer = setInterval(() => {
                        step++;
                        current = Math.min(Math.round(increment * step), target);
                        counter.textContent = current.toLocaleString() + suffix;
                        if (current >= target) clearInterval(timer);
                    }, duration / steps);
                    observer.unobserve(counter);
                }
            });
        }, { threshold: 0.3 });
        observer.observe(counter);
    });
});
</script>
@endpush
