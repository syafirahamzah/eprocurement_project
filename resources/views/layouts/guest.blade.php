<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Procurement Albizi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-purple-700 via-indigo-800 to-blue-900 min-h-screen text-white font-sans flex flex-col">

    <!-- Navbar -->
    <nav class="flex justify-between items-center px-8 py-4 bg-opacity-30 backdrop-blur-md bg-black/30 shadow-md">
        <div class="text-2xl font-bold">SH</div>
        
        <ul class="flex space-x-8 text-sm font-semibold">
            <li><a href="/" class="hover:text-indigo-300 transition">Beranda</a></li>
            <li><a href="#tentang" class="hover:text-indigo-300 transition">Tentang Kami</a></li>
            <li>
                @guest
                    <a href="{{ route('login') }}" class="hover:text-indigo-300 transition">
                        Konsumen
                    </a>
                @else
                    @if(Auth::user()->role === 'customer')
                        <a href="{{ route('customer.dashboard') }}" class="hover:text-indigo-300 transition">
                            Konsumen
                        </a>
                    @endif
                @endguest
            </li>
            <li><a href="{{ route('login') }}" class="hover:text-indigo-300 transition">Supplier</a></li>
        </ul>
    </nav>

    <!-- Dynamic Content -->
    <main class="flex-grow flex items-center justify-center text-center px-6 py-16">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="text-center text-sm text-gray-300 py-4">
        &copy; {{ date('Y') }} Sistem E-Procurement Syafira
    </footer>

</body>
</html>
