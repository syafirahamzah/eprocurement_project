<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard') | Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-[#2c3e50] text-white fixed h-screen px-4 py-6">
        <h2 class="text-xl font-bold mb-6">E-Procurement Admin</h2>
        <ul class="space-y-3">
            <li>
                <a href="{{ route('admin.dashboard') }}"
                   class="block px-2 py-1 hover:bg-blue-700 rounded">
                   🏠 Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.monitoring') }}"
                   class="block px-2 py-1 hover:bg-blue-700 rounded">
                   📊 Semua Orders
                </a>
            </li>
            <li>
                <a href="{{ route('admin.customers.index') }}"
                   class="block px-2 py-1 hover:bg-blue-700 rounded">
                   👥 Data Customer
                </a>
            </li>
            <li>
                <a href="{{ route('admin.vendors.index') }}"
                   class="block px-2 py-1 hover:bg-blue-700 rounded">
                   🏪 Data Vendor
                </a>
            </li>
            <li>
                <a href="{{ route('admin.products') }}"
                   class="block px-2 py-1 hover:bg-blue-700 rounded">
                   📦 Semua Produk
                </a>
            </li>
            <li>
                <a href="{{ route('admin.profile') }}"
                   class="block px-2 py-1 hover:bg-blue-700 rounded">
                   ⚙ Profil Admin
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 ml-64">
        <!-- Navbar -->
        <nav class="w-full bg-white shadow px-6 py-4 flex justify-between items-center">
            <div class="font-semibold text-lg"></div>
            <div class="flex items-center space-x-4">
                <span class="text-gray-600">👤 {{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="inline">@csrf
                    <button class="text-red-600 hover:underline text-sm">Logout</button>
                </form>
            </div>
        </nav>

        <!-- Konten Halaman -->
        <main class="p-6">
            @yield('content')
        </main>
    </div>

</body>
</html>
