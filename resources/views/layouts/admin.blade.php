<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DiveForge') }} - Admin Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .sidebar-transition {
            transition: all 0.3s ease;
        }
        .admin-card {
            transition: all 0.3s ease;
        }
        .admin-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-blue-900 text-white w-64 sidebar-transition">
            <!-- Logo -->
            <div class="p-6 border-b border-blue-800">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-water text-2xl text-blue-300"></i>
                    <h1 class="text-xl font-bold">DiveForge</h1>
                </div>
                <p class="text-blue-300 text-sm mt-1">Admin Dashboard</p>
            </div>

            <!-- Navigation -->
            <nav class="mt-6">
                <div class="px-4 py-2 text-blue-300 text-xs uppercase tracking-wide font-semibold">
                    Main Menu
                </div>
                
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-800 hover:text-white transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-800 border-r-4 border-blue-400' : '' }}">
                    <i class="fas fa-chart-line mr-3"></i>
                    Dashboard
                </a>

                <!-- Users -->
                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-800 hover:text-white transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-blue-800 border-r-4 border-blue-400' : '' }}">
                    <i class="fas fa-users mr-3"></i>
                    Users
                </a>

                <!-- Dive Shops -->
                <a href="{{ route('admin.shops.index') }}" 
                   class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-800 hover:text-white transition-colors {{ request()->routeIs('admin.shops.*') ? 'bg-blue-800 border-r-4 border-blue-400' : '' }}">
                    <i class="fas fa-store mr-3"></i>
                    Dive Shops
                </a>

                <!-- Divider -->
                <div class="px-4 py-2 text-blue-300 text-xs uppercase tracking-wide font-semibold mt-6">
                    Operations
                </div>

                <!-- Equipment -->
                <a href="#" class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-800 hover:text-white transition-colors">
                    <i class="fas fa-swimming-pool mr-3"></i>
                    Equipment
                    <span class="ml-auto bg-blue-700 text-xs px-2 py-1 rounded-full">Soon</span>
                </a>

                <!-- Courses -->
                <a href="#" class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-800 hover:text-white transition-colors">
                    <i class="fas fa-graduation-cap mr-3"></i>
                    Courses
                    <span class="ml-auto bg-blue-700 text-xs px-2 py-1 rounded-full">Soon</span>
                </a>

                <!-- Bookings -->
                <a href="#" class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-800 hover:text-white transition-colors">
                    <i class="fas fa-calendar-check mr-3"></i>
                    Bookings
                    <span class="ml-auto bg-blue-700 text-xs px-2 py-1 rounded-full">Soon</span>
                </a>

                <!-- Reports -->
                <a href="#" class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-800 hover:text-white transition-colors">
                    <i class="fas fa-chart-bar mr-3"></i>
                    Reports
                    <span class="ml-auto bg-blue-700 text-xs px-2 py-1 rounded-full">Soon</span>
                </a>

                <!-- Divider -->
                <div class="px-4 py-2 text-blue-300 text-xs uppercase tracking-wide font-semibold mt-6">
                    Settings
                </div>

                <!-- Settings -->
                <a href="#" class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-800 hover:text-white transition-colors">
                    <i class="fas fa-cog mr-3"></i>
                    Settings
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <button id="sidebar-toggle" class="text-gray-500 hover:text-gray-700 lg:hidden">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h2 class="text-xl font-semibold text-gray-800">
                            @yield('page-title', 'Dashboard')
                        </h2>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="relative text-gray-500 hover:text-gray-700">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                        </button>

                        <!-- User Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-medium">{{ auth()->user()->initials }}</span>
                                </div>
                                <span class="hidden md:block">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-sm"></i>
                            </button>

                            <div x-show="open" @click.away="open = false" x-transition
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i>Profile
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2"></i>Settings
                                </a>
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Alpine.js for interactive components -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebar-toggle')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        });
    </script>

    @stack('scripts')
</body>
</html>
