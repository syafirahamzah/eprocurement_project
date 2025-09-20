<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard Vendor') E-Procurement</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
    <!-- Sidebar -->
    <aside class="sidebar w-60 bg-[#66785F] dark:bg-gray-800 shadow-lg flex flex-col justify-between transition-all duration-300">

        <div>
            <div class="bg-[#4B5945] p-4 font-bold text-2xl text-white dark:border-gray-900 shadow-lg">E-PROC</div>
            <nav class="p-4 space-y-2 text-sm font-medium">
                <a href="{{ route('vendor.dashboard') }}" class="flex items-center text-white space-x-2 p-2 rounded hover:bg-[#B2C9AD] dark:hover:bg-gray-700">
                   <i data-lucide="home" class="w-6 h-6 text-white"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('vendor.products.index') }}" class="flex items-center text-white space-x-2 p-2 rounded hover:bg-[#B2C9AD] dark:hover:bg-gray-700">
                    <i data-lucide="package" class="w-6 h-6 text-white"></i>
                    <span>Produk</span>
                </a>

                </a>
    <a href="{{ route('vendor.orders.index') }}"
       class="flex items-center text-white space-x-2 p-2 rounded hover:bg-[#B2C9AD] dark:hover:bg-gray-700"> 
       <i data-lucide="file-text" class="w-6 h-6 text-white"></i>
       <span>Pesanan Masuk</span>
    </a>
</li>
<a href="{{ route('vendor.requests.history') }}"
       class="flex items-center text-white space-x-2 p-2 rounded hover:bg-[#B2C9AD] dark:hover:bg-gray-700"> 
       <i data-lucide="history" class="w-6 h-6 text-white"></i>
       <span>Riwayat pesanan</span>
    </a>
</li>
                
<a href="{{ route('vendor.notifications.index') }}" class="flex items-center text-white space-x-2 p-2 rounded hover:bg-[#B2C9AD] dark:hover:bg-gray-700">
        <div class="flex items-center">
            <i data-lucide="bell" class="w-6 h-6 text-white"></i>
            <span class="mr-2"></span> Notifikasi
        </div>
        @if($unreadCount > 0)
            <span class="ml-auto inline-block bg-red-600 text-white text-xs px-2 py-0.5 rounded-full">
                {{ $unreadCount }}
            </span>
        @endif
    </a>

    <a href="{{ route('vendor.negotiation.index') }}" class="flex items-center text-white space-x-2 p-2 rounded hover:bg-[#B2C9AD] dark:hover:bg-gray-700">
        <i data-lucide="message-circle" class="w-6 h-6 text-white"></i>
         <span>Negosiasi</span>
         @isset($newMessageCount)
    <span class="bg-red-600 text-white rounded-full px-2 text-xs">{{ $newMessageCount }}</span>
@endisset

<a href="{{ route('vendor.monitoring') }}"
       class="flex items-center text-white space-x-2 p-2 rounded hover:bg-[#B2C9AD] dark:hover:bg-gray-700"> 
       <i data-lucide="bar-chart" class="w-6 h-6 text-white"></i>
       <span>Monitoring</span>
    </a>
</li>

        <div class="p-4">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full hover p-2 text-center text-white font-semibold hover:bg-[#91AC8F] rounded-md">Logout</button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col content transition-all duration-300">
        <!-- Navbar -->
        <header class="bg-[#4B5945] dark:bg-gray-800 shadow-lg px-6 py-4 flex justify-between items-center relative">
            <button onclick="toggleSidebar()" class="text-white dark:text-gray-200 hover:text-gray-800 dark:hover:text-white focus:outline-none">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>

            <div class="flex items-center space-x-4 relative">
                <span class="text-sm text-white">{{ Auth::user()->name }}</span>
                <div id="profileDropdownButton" class="w-8 h-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center text-white font-bold uppercase cursor-pointer select-none">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>

       <!-- Dropdown Menu -->
<div id="profileDropdownMenu" class="hidden absolute right-0 mt-10 w-40 bg-white dark:bg-gray-700 rounded shadow-lg py-2 z-50">
    <a href="{{ route('vendor.profile') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600">
        Profil
    </a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600">
            Logout
        </button>
    </form>
</div>

            </div>
        </header>


        <!-- Page Content -->
        <main class="px-0 m-0">
           

            @yield('content')
        </main>
    </div>
</div>

<!-- Lucide Icon Support -->
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
    function toggleSidebar() {
        const app = document.getElementById('app-layout');
        if (app.classList.contains('sidebar-collapsed')) {
            app.classList.remove('sidebar-collapsed');
        } else {
            app.classList.add('sidebar-collapsed');
        }
    }
const dropdownButton = document.getElementById('profileDropdownButton');
    const dropdownMenu = document.getElementById('profileDropdownMenu');

    dropdownButton.addEventListener('click', function(event) {
        event.stopPropagation();
        dropdownMenu.classList.toggle('hidden');
    });

    // Tutup dropdown kalau klik di luar
    document.addEventListener('click', function() {
        if (!dropdownMenu.classList.contains('hidden')) {
            dropdownMenu.classList.add('hidden');
        }
    });
</script>

</body>
</html>
