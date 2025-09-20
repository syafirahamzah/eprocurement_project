<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Procurement</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-gray-50 to-white min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-darkGreen shadow">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="text-xl font-bold text-white">E-Procurement</div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="flex-grow flex items-center justify-center px-4 text-center">
        <div class="max-w-xl">
            <h1 class="text-3xl md:text-5xl font-extrabold text-darkGreen mb-4">Selamat Datang</h1>
            <p class="text-gray-700 text-lg md:text-xl mb-6">
                Sistem pengadaan barang .
            </p>
            <p class="text-gray-600 mb-6">Silakan pilih peran Anda untuk masuk ke sistem:</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('login.customer') }}"
                   class="bg-darkGreen text-white px-6 py-2 rounded-lg hover:bg-green-900 transition inline-flex items-center justify-center gap-2">
                    <span class="material-icons">Konsumen</span> 
                </a>
                <a href="{{ route('login.vendor') }}"
                   class="bg-gray-200 text-darkGreen px-6 py-2 rounded-lg hover:bg-gray-300 transition inline-flex items-center justify-center gap-2">
                    <span class="material-icons">Vendor</span>
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-100 text-center py-4 text-sm text-gray-600 shadow-inner">
        &copy; {{ date('Y') }} E-Procurement. Syafira project
    </footer>

</body>
</html>
