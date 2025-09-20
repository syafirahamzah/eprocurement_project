<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard Customer') E-Procurement</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>

    <style>
        .sidebar-collapsed .sidebar {
            width: 0 !important;
            overflow: hidden;
        }
        .sidebar-collapsed .content {
            margin-left: 0 !important;
        }
    </style>
    
</head>
 @yield('scripts')
<body class="bg-[#F5F5F0] dark:bg-gray-900 text-black dark:text-gray-100">

<div class="flex min-h-screen" id="app-layout">
    
    {{-- Sidebar --}}
    <aside class="sidebar w-60 bg-[#66785F] dark:bg-gray-800 shadow-lg flex flex-col justify-between transition-all duration-300">
        <div>
            <div class="bg-[#4B5945] p-4 font-bold text-2xl text-white shadow-lg">E-PROC</div>
            
            <nav class="p-4 space-y-2 text-sm font-medium">
                {{-- Navigasi Utama --}}
                <a href="{{ route('customer.dashboard') }}" class="flex items-center text-white space-x-2 p-2 rounded hover:bg-[#B2C9AD] dark:hover:bg-gray-700">
                    <i data-lucide="home" class="w-6 h-6 text-white"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('customer.vendor.select') }}" class="flex items-center text-white space-x-2 p-2 rounded hover:bg-[#B2C9AD] dark:hover:bg-gray-700">
                    <i data-lucide="users" class="w-6 h-6 text-white"></i>
                    <span>Pilih Vendor</span>
                </a>

                <a href="{{ route('customer.eCatalog') }}" class="flex items-center text-white space-x-2 p-2 rounded hover:bg-[#B2C9AD] dark:hover:bg-gray-700">
                    <i data-lucide="shopping-bag" class="w-6 h-6 text-white"></i>
                    <span>E-Katalog</span>
                </a>
                <a href="{{ route('customer.orders') }}" class="flex items-center text-white space-x-2 p-2 rounded hover:bg-[#B2C9AD] dark:hover:bg-gray-700">
                    <i data-lucide="file-text" class="w-6 h-6 text-white"></i>
                    <span>Daftar Pemesanan</span>
                </a>

               
                <a href="{{ route('customer.notifications') }}" class="relative flex items-center text-white space-x-2 p-2 rounded hover:bg-[#B2C9AD] dark:hover:bg-gray-700">
                    <i data-lucide="bell" class="w-6 h-6 text-white"></i>
                    <span>Notifikasi</span>
                    @if($unreadNotifications = auth()->user()->notifications()->where('is_read', false)->count())
                        <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full px-2 py-0.5">{{ $unreadNotifications }}</span>
                    @endif
                </a>

                <a href="{{ route('customer.monitoring') }}" class="flex items-center text-white space-x-2 p-2 rounded hover:bg-[#B2C9AD] dark:hover:bg-gray-700">
                    <i data-lucide="bar-chart" class="w-6 h-6 text-white"></i>
                    <span>Monitoring</span>
                </a>

                <a href="{{ route('customer.history') }}" class="flex items-center text-white space-x-2 p-2 rounded hover:bg-[#B2C9AD] dark:hover:bg-gray-700">
                    <i data-lucide="archive" class="w-6 h-6 text-"></i>
                    <span>Riwayat pesanan</span>
                </a>
            </nav>
        </div>

        {{-- Logout --}}
        <div class="p-4">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full p-2 text-center text-white font-semibold hover:bg-[#91AC8F] rounded-md">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col content transition-all duration-300">
        
        {{-- Navbar --}}
        <header class="bg-[#4B5945] dark:bg-gray-800 shadow-lg px-6 py-4 flex justify-between items-center relative">
            <button onclick="toggleSidebar()" class="text-white hover:text-gray-300">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>

            <div class="flex items-center space-x-4 relative">
                <span class="text-sm text-white">{{ Auth::user()->name }}</span>
                <div id="profileDropdownButton" class="w-8 h-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center text-white font-bold uppercase cursor-pointer">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>

                {{-- Dropdown Profil --}}
                <div id="profileDropdownMenu" class="hidden absolute right-0 mt-12 w-40 bg-white dark:bg-gray-700 rounded shadow-lg py-2 z-50">
                    <a href="{{ route('customer.profile') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600">
                        Profil
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- Konten Halaman --}}
        <main class="px-0 m-0">

            @yield('content')
        </main>
    </div>
</div>

{{-- Icon --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();

    function toggleSidebar() {
        const app = document.getElementById('app-layout');
        app.classList.toggle('sidebar-collapsed');
    }

    const dropdownButton = document.getElementById('profileDropdownButton');
    const dropdownMenu = document.getElementById('profileDropdownMenu');

    dropdownButton.addEventListener('click', function (e) {
        e.stopPropagation();
        dropdownMenu.classList.toggle('hidden');
    });

    document.addEventListener('click', function () {
        dropdownMenu.classList.add('hidden');
    });
</script>
</body>
</html>
