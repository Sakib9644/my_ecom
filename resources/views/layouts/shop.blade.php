<!DOCTYPE html>
<html lang="en" class="h-full bg-gradient-to-br from-slate-100 via-slate-50 to-slate-200">
<head>
    @php $faviconPath = \App\Models\Setting::get('favicon'); $faviconUrl = $faviconPath ? \Illuminate\Support\Facades\Storage::url($faviconPath) : null; @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TechEx Store') | {{ \App\Models\Setting::get('site_name', 'TechEx Store') }}</title>
    @if($faviconUrl)
    <link rel="icon" type="image/png" href="{{ $faviconUrl }}">
    @endif
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @stack('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="flex flex-col h-full text-slate-800">

    <!-- Header Navbar -->
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-8">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 text-xl font-bold tracking-tight text-slate-900">
                        <span class="p-2 bg-primary-600 text-white rounded-xl shadow-md shadow-primary-500/20">
                            <i class="fa-solid fa-bolt"></i>
                        </span>
                        <span>{{ \App\Models\Setting::get('site_name', 'TechEx') }}<span class="text-primary-600">.</span></span>
                    </a>
                    
                    <div class="hidden md:flex items-center gap-6">
                        <a href="{{ route('home') }}" class="text-sm font-semibold transition-colors {{ request()->routeIs('home') ? 'text-primary-600' : 'text-slate-600 hover:text-slate-900' }}">Home</a>
                        <a href="{{ route('products.index') }}" class="text-sm font-semibold transition-colors {{ request()->routeIs('products.index') ? 'text-primary-600' : 'text-slate-600 hover:text-slate-900' }}">Shop</a>
                        <a href="{{ route('orders.index') }}" class="text-sm font-semibold transition-colors {{ request()->routeIs('orders.index') ? 'text-primary-600' : 'text-slate-600 hover:text-slate-900' }}">My Orders</a>
                    </div>
                </div>

                <!-- Right Nav -->
                <div class="flex items-center gap-4">
                    <!-- Search Form Bar -->
                    <form action="{{ route('products.index') }}" method="GET" class="hidden sm:flex relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="pl-9 pr-4 py-1.5 w-60 rounded-full border border-slate-300 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    </form>

                    <!-- Cart Link -->
                    <a href="{{ route('cart.index') }}" class="relative p-2.5 text-slate-600 hover:text-primary-600 transition-colors">
                        <i class="fa-solid fa-cart-shopping text-lg"></i>
                        @php $cartCount = count(session('cart', [])); @endphp
                        @if($cartCount > 0)
                            <span id="cart-count" class="absolute top-0 right-0 flex h-5 w-5 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white ring-2 ring-white">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    <div class="h-6 w-px bg-slate-200"></div>

                    <!-- Auth Dropdown / Buttons -->
                    @auth
                        <div class="relative" id="user-menu-wrapper">
                            <button
                                id="user-menu-btn"
                                onclick="toggleUserMenu()"
                                class="flex items-center gap-1.5 text-sm font-semibold text-slate-700 hover:text-slate-900 focus:outline-none select-none"
                            >
                                <i class="fa-regular fa-circle-user text-lg"></i>
                                <span>{{ Auth::user()->name }}</span>
                                <i id="user-menu-chevron" class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"></i>
                            </button>

                            <div
                                id="user-menu-dropdown"
                                class="absolute right-0 top-full mt-1 w-52 origin-top-right rounded-2xl bg-white p-1.5 shadow-xl ring-1 ring-black/10 hidden z-50"
                            >
                                <div class="px-4 py-2 border-b border-slate-100 mb-1">
                                    <p class="text-xs font-bold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-[10px] text-slate-400 truncate">{{ Auth::user()->email }}</p>
                                </div>

                                @if(Auth::user()->is_admin)
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-primary-50 hover:text-primary-700 rounded-xl transition-colors">
                                        <i class="fa-solid fa-gauge-high text-slate-400 w-4 text-center"></i> Admin Panel
                                    </a>
                                @endif

                                <a href="{{ route('orders.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 rounded-xl transition-colors">
                                    <i class="fa-solid fa-box text-slate-400 w-4 text-center"></i> My Orders
                                </a>

                                <div class="border-t border-slate-100 mt-1 pt-1">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50 rounded-xl transition-colors text-left">
                                            <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i> Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">Sign in</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-primary-600 text-white rounded-full text-sm font-semibold hover:bg-primary-700 hover:shadow-lg hover:shadow-primary-500/20 transition-all">Sign up</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-10 border-t border-slate-800 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="space-y-4">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 text-xl font-bold tracking-tight text-white">
                        <span class="p-2 bg-primary-600 text-white rounded-xl shadow-md shadow-primary-500/20">
                            <i class="fa-solid fa-bolt text-sm"></i>
                        </span>
                        <span>{{ \App\Models\Setting::get('site_name', 'TechEx') }}<span class="text-primary-600">.</span></span>
                    </a>
                    <p class="text-sm">Premium quality electronics, fashion, and lifestyle essentials delivered right to your doorstep.</p>
                </div>
                <div>
                    <h3 class="text-white font-semibold text-sm mb-4 uppercase tracking-wider">Quick Links</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('products.index') }}" class="hover:text-white transition-colors">All Products</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Sign In</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold text-sm mb-4 uppercase tracking-wider">Customer Care</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Shipping & Returns</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold text-sm mb-4 uppercase tracking-wider">Contact Info</h3>
                    <p class="text-sm mb-2"><i class="fa-regular fa-envelope mr-2"></i> {{ \App\Models\Setting::get('contact_email', 'support@techex.com') }}</p>
                    <p class="text-sm"><i class="fa-solid fa-phone mr-2"></i> +1 (800) 123-4567</p>
                </div>
            </div>
            <div class="border-t border-slate-800 mt-12 pt-6 text-center text-xs">
                &copy; {{ date('Y') }} {{ \App\Models\Setting::get('site_name', 'TechEx Store') }}. All rights reserved.
            </div>
        </div>
    </footer>

    @stack('scripts')
    @yield('scripts')

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: @json(session('success')),
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
            background: '#f0fdf4',
            color: '#166534',
            iconColor: '#16a34a',
            customClass: {
                popup: 'rounded-2xl shadow-xl border border-green-200'
            }
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: @json(session('error')),
            timer: 4000,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
            background: '#fef2f2',
            color: '#991b1b',
            iconColor: '#dc2626',
            customClass: {
                popup: 'rounded-2xl shadow-xl border border-red-200'
            }
        });
    </script>
    @endif

    <!-- AOS Animation Init -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 100,
            easing: 'ease-out-cubic',
        });
    </script>

    <script>
        function toggleUserMenu() {
            const dropdown = document.getElementById('user-menu-dropdown');
            const chevron  = document.getElementById('user-menu-chevron');
            const isHidden = dropdown.classList.contains('hidden');

            if (isHidden) {
                dropdown.classList.remove('hidden');
                chevron.style.transform = 'rotate(180deg)';
            } else {
                dropdown.classList.add('hidden');
                chevron.style.transform = 'rotate(0deg)';
            }
        }

        // Close when clicking outside
        document.addEventListener('click', function(e) {
            const wrapper  = document.getElementById('user-menu-wrapper');
            const dropdown = document.getElementById('user-menu-dropdown');
            if (wrapper && dropdown && !wrapper.contains(e.target)) {
                dropdown.classList.add('hidden');
                const chevron = document.getElementById('user-menu-chevron');
                if (chevron) chevron.style.transform = 'rotate(0deg)';
            }
        });
    </script>
</body>
</html>
