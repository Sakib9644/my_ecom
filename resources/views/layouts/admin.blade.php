<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php $faviconPath = \App\Models\Setting::get('favicon'); $faviconUrl = $faviconPath ? \Illuminate\Support\Facades\Storage::url($faviconPath) : null; @endphp
    <title>Admin Dashboard | {{ \App\Models\Setting::get('site_name', 'TechEx Store') }}</title>
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
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    @stack('styles')
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        /* DataTables Custom Styling for Admin Theme */
        .datatable-table thead th {
            padding: 16px 24px !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            color: #94a3b8 !important;
            background: #f8fafc !important;
            border-bottom: 1px solid #e2e8f0 !important;
            text-align: left !important;
        }
        .datatable-table tbody td {
            padding: 16px 24px !important;
            font-size: 14px !important;
            color: #334155 !important;
            border-bottom: 1px solid #f1f5f9 !important;
        }
        .datatable-table tbody tr:hover {
            background: #f8fafc !important;
        }
        .datatable-table .dataTables_empty {
            padding: 64px !important;
            text-align: center !important;
            color: #94a3b8 !important;
        }
        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_filter label {
            font-size: 13px !important;
            font-weight: 600 !important;
            color: #64748b !important;
        }
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #cbd5e1 !important;
            border-radius: 12px !important;
            padding: 6px 12px !important;
            font-size: 13px !important;
            outline: none !important;
            background: white !important;
        }
        .dataTables_wrapper .dataTables_length select:focus,
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #0ea5e9 !important;
            box-shadow: 0 0 0 2px rgba(14,165,233,0.2) !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 10px !important;
            padding: 6px 14px !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            border: 1px solid #e2e8f0 !important;
            color: #64748b !important;
            background: white !important;
            margin: 0 2px !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f1f5f9 !important;
            border-color: #cbd5e1 !important;
            color: #334155 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #0ea5e9 !important;
            border-color: #0ea5e9 !important;
            color: white !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #0284c7 !important;
            border-color: #0284c7 !important;
        }
        .dataTables_wrapper .dataTables_info {
            font-size: 13px !important;
            color: #94a3b8 !important;
            font-weight: 500 !important;
            padding-top: 16px !important;
        }
        .dataTables_wrapper .dataTables_processing {
            background: rgba(255,255,255,0.9) !important;
            border-radius: 12px !important;
            font-size: 13px !important;
            color: #0ea5e9 !important;
        }
        /* Sidebar collapse transitions */
        .sidebar-transition {
            transition: width 0.25s ease, padding 0.25s ease;
        }
        .sidebar-text-transition {
            transition: opacity 0.2s ease, margin 0.25s ease;
        }
    </style>
</head>
<body class="flex h-full text-slate-800">

    <!-- Sidebar -->
    <aside id="admin-sidebar" class="hidden md:flex flex-col bg-slate-900 text-slate-400 border-r border-slate-800 sidebar-transition" style="width: 256px;">
        <div class="h-16 flex items-center px-4 border-b border-slate-800 bg-slate-950 shrink-0">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-lg font-bold text-white tracking-tight truncate">
                <span class="p-1.5 bg-primary-600 text-white rounded-lg shrink-0">
                    <i class="fa-solid fa-bolt text-xs"></i>
                </span>
                <span id="sidebar-brand-text" class="sidebar-text-transition">{{ \App\Models\Setting::get('site_name', 'TechEx') }} Admin</span>
            </a>
        </div>
        <nav class="flex-grow p-3 space-y-1 overflow-y-auto overflow-x-hidden">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl transition-all whitespace-nowrap {{ request()->routeIs('admin.dashboard') ? 'bg-primary-600 text-white shadow-lg shadow-primary-500/20' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-gauge-high w-5 text-center shrink-0"></i>
                <span class="sidebar-text-transition sidebar-link-text">Dashboard</span>
            </a>
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl transition-all whitespace-nowrap {{ request()->routeIs('admin.products.*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-500/20' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-box w-5 text-center shrink-0"></i>
                <span class="sidebar-text-transition sidebar-link-text">Products</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl transition-all whitespace-nowrap {{ request()->routeIs('admin.categories.*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-500/20' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-layer-group w-5 text-center shrink-0"></i>
                <span class="sidebar-text-transition sidebar-link-text">Categories</span>
            </a>
            <a href="{{ route('admin.sizes.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl transition-all whitespace-nowrap {{ request()->routeIs('admin.sizes.*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-500/20' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-ruler w-5 text-center shrink-0"></i>
                <span class="sidebar-text-transition sidebar-link-text">Sizes</span>
            </a>
            <a href="{{ route('admin.colors.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl transition-all whitespace-nowrap {{ request()->routeIs('admin.colors.*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-500/20' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-palette w-5 text-center shrink-0"></i>
                <span class="sidebar-text-transition sidebar-link-text">Colors</span>
            </a>
            <a href="{{ route('admin.sliders.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl transition-all whitespace-nowrap {{ request()->routeIs('admin.sliders.*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-500/20' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-images w-5 text-center shrink-0"></i>
                <span class="sidebar-text-transition sidebar-link-text">Sliders</span>
            </a>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl transition-all whitespace-nowrap {{ request()->routeIs('admin.orders.*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-500/20' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-cart-shopping w-5 text-center shrink-0"></i>
                <span class="sidebar-text-transition sidebar-link-text">Orders</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl transition-all whitespace-nowrap {{ request()->routeIs('admin.users.*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-500/20' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-users w-5 text-center shrink-0"></i>
                <span class="sidebar-text-transition sidebar-link-text">Users</span>
            </a>
            <!-- Settings -->
            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl transition-all whitespace-nowrap {{ request()->routeIs('admin.settings.*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-500/20' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-gear w-5 text-center shrink-0"></i>
                <span class="sidebar-text-transition sidebar-link-text">Settings</span>
            </a>
            <div class="pt-3 border-t border-slate-800 mt-3">
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl whitespace-nowrap hover:bg-slate-800 hover:text-white transition-all">
                    <i class="fa-solid fa-globe w-5 text-center shrink-0"></i>
                    <span class="sidebar-text-transition sidebar-link-text">View Website</span>
                </a>
            </div>
        </nav>
        <div class="p-3 border-t border-slate-800 bg-slate-950 shrink-0">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex w-full items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl whitespace-nowrap text-red-400 hover:bg-red-950/30 hover:text-red-300 transition-all text-left">
                    <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center shrink-0"></i>
                    <span class="sidebar-text-transition sidebar-link-text">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Admin Workspace -->
    <div class="flex-grow flex flex-col min-w-0">
        <!-- Top bar -->
        <header class="h-16 bg-white border-b border-slate-200 flex justify-between items-center px-4 sm:px-6">
            <div class="flex items-center gap-3">
                <!-- Sidebar toggle (desktop) -->
                <button id="sidebar-toggle" class="hidden md:flex text-slate-500 hover:text-slate-800 focus:outline-none p-2 rounded-xl hover:bg-slate-100 transition-colors" title="Toggle sidebar">
                    <i class="fa-solid fa-bars-staggered text-lg"></i>
                </button>

                <!-- Mobile toggle button -->
                <button id="mobile-sidebar-toggle" class="md:hidden text-slate-600 hover:text-slate-900 focus:outline-none p-2 rounded-xl hover:bg-slate-100 transition-colors">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>

                <h1 class="text-lg font-bold text-slate-800">@yield('page_title', 'Admin Dashboard')</h1>
            </div>
            
            <div class="flex items-center gap-3 sm:gap-4">
                <span class="text-sm font-semibold text-slate-600 hidden sm:block">{{ Auth::user()->name }}</span>
                <a href="{{ route('admin.profile.edit') }}" class="block h-9 w-9 rounded-full overflow-hidden bg-primary-600 text-white flex items-center justify-center font-bold text-sm ring-2 ring-transparent hover:ring-primary-300 transition-all {{ request()->routeIs('admin.profile.*') ? 'ring-primary-400' : '' }}">
                    <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="h-full w-full object-cover">
                </a>
            </div>
        </header>

        <!-- Dynamic Content -->
        <main class="flex-grow p-6 overflow-y-auto">
            @yield('content')
        </main>
    </div>

    <!-- Mobile sidebar overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden" onclick="toggleMobileSidebar()"></div>

    <!-- Mobile sidebar -->
    <aside id="mobile-sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-400 border-r border-slate-800 transform -translate-x-full transition-transform duration-200 ease-in-out md:hidden flex flex-col">
        <div class="h-16 flex items-center justify-between px-4 border-b border-slate-800 bg-slate-950 shrink-0">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-lg font-bold text-white tracking-tight">
                <span class="p-1.5 bg-primary-600 text-white rounded-lg">
                    <i class="fa-solid fa-bolt text-xs"></i>
                </span>
                <span>{{ \App\Models\Setting::get('site_name', 'TechEx') }} Admin</span>
            </a>
            <button onclick="toggleMobileSidebar()" class="text-slate-400 hover:text-white p-1">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <nav class="flex-grow p-3 space-y-1 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-primary-600 text-white shadow-lg shadow-primary-500/20' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-gauge-high w-5 text-center"></i> Dashboard
            </a>
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.products.*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-500/20' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-box w-5 text-center"></i> Products
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-500/20' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-layer-group w-5 text-center"></i> Categories
            </a>
            <a href="{{ route('admin.sizes.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.sizes.*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-500/20' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-ruler w-5 text-center"></i> Sizes
            </a>
            <a href="{{ route('admin.colors.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.colors.*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-500/20' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-palette w-5 text-center"></i> Colors
            </a>
            <a href="{{ route('admin.sliders.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.sliders.*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-500/20' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-images w-5 text-center"></i> Sliders
            </a>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.orders.*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-500/20' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-cart-shopping w-5 text-center"></i> Orders
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.users.*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-500/20' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-users w-5 text-center"></i> Users
            </a>
            <div class="pt-3 border-t border-slate-800 mt-3">
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl hover:bg-slate-800 hover:text-white transition-all">
                    <i class="fa-solid fa-globe w-5 text-center"></i> View Website
                </a>
            </div>
        </nav>
        <div class="p-3 border-t border-slate-800 bg-slate-950 shrink-0">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex w-full items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl text-red-400 hover:bg-red-950/30 hover:text-red-300 transition-all text-left">
                    <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- jQuery (required by DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scripts')
    @yield('scripts')

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

    <script>
        // Desktop sidebar toggle
        const sidebar = document.getElementById('admin-sidebar');
        const sidebarLinks = document.querySelectorAll('.sidebar-link-text');
        const sidebarBrand = document.getElementById('sidebar-brand-text');
        const toggleBtn = document.getElementById('sidebar-toggle');

        function setSidebarState(collapsed) {
            if (collapsed) {
                sidebar.style.width = '64px';
                sidebarLinks.forEach(el => el.style.display = 'none');
                if (sidebarBrand) sidebarBrand.style.display = 'none';
                localStorage.setItem('admin_sidebar_collapsed', 'true');
            } else {
                sidebar.style.width = '256px';
                sidebarLinks.forEach(el => el.style.display = '');
                if (sidebarBrand) sidebarBrand.style.display = '';
                localStorage.setItem('admin_sidebar_collapsed', 'false');
            }
        }

        if (toggleBtn) {
            // Restore state on load
            if (localStorage.getItem('admin_sidebar_collapsed') === 'true') {
                setSidebarState(true);
            }

            toggleBtn.addEventListener('click', function() {
                setSidebarState(sidebar.style.width !== '64px');
            });
        }

        // Mobile sidebar toggle
        function toggleMobileSidebar() {
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const isOpen = !mobileSidebar.classList.contains('-translate-x-full');
            if (isOpen) {
                mobileSidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            } else {
                mobileSidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            }
        }

        document.getElementById('mobile-sidebar-toggle')?.addEventListener('click', toggleMobileSidebar);
    </script>
</body>
</html>
