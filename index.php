<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Soccer Booking System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <span class="font-bold text-2xl text-emerald-600">⚽ MiniSoccer</span>
                    </div>
                </div>
                <div class="hidden md:flex items-center">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="user/dashboard.php" class="text-slate-600 hover:text-emerald-600 px-3 py-2 rounded-md font-medium transition">Dashboard</a>
                        <a href="auth/logout.php" class="ml-4 bg-red-50 text-red-600 hover:bg-red-100 px-4 py-2 rounded-lg font-medium transition">Logout</a>
                    <?php else: ?>
                        <a href="auth/login.php" class="text-slate-600 hover:text-emerald-600 px-3 py-2 rounded-md font-medium transition">Masuk</a>
                        <a href="auth/register.php" class="ml-4 bg-emerald-600 text-white hover:bg-emerald-700 px-5 py-2.5 rounded-lg font-medium transition shadow-sm hover:shadow-md">Daftar</a>
                    <?php endif; ?>
                </div>
                <!-- Mobile menu button -->
                <div class="flex items-center md:hidden">
                    <button type="button" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-emerald-500">
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu -->
        <div class="hidden md:hidden bg-white border-t border-slate-200" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="user/dashboard.php" class="text-slate-600 hover:bg-slate-50 block px-3 py-2 rounded-md text-base font-medium">Dashboard</a>
                    <a href="auth/logout.php" class="text-red-600 hover:bg-red-50 block px-3 py-2 rounded-md text-base font-medium">Logout</a>
                <?php else: ?>
                    <a href="auth/login.php" class="text-slate-600 hover:bg-slate-50 block px-3 py-2 rounded-md text-base font-medium">Masuk</a>
                    <a href="auth/register.php" class="bg-emerald-50 text-emerald-700 block px-3 py-2 rounded-md text-base font-medium">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative bg-white overflow-hidden border-b border-slate-200">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32 pt-10 sm:pt-16 lg:pt-20">
                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-slate-900 sm:text-5xl md:text-6xl">
                            <span class="block xl:inline">Sewa Lapangan</span>
                            <span class="block text-emerald-600 mt-2">Mini Soccer Impianmu</span>
                        </h1>
                        <p class="mt-3 text-base text-slate-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            Cek jadwal ketersediaan lapangan secara real-time dan pesan langsung dari perangkat Anda. Nikmati fasilitas terbaik untuk permainan terbaik Anda.
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            <div class="rounded-md shadow">
                                <a href="auth/login.php" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 md:py-4 md:text-lg md:px-10 transition duration-300 ease-in-out transform hover:-translate-y-1">
                                    Pesan Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
            <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="assets/images/hero.png" alt="Mini Soccer Field">
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-base text-emerald-600 font-semibold tracking-wide uppercase">Fasilitas Kami</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                    Kenapa Memilih Kami?
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Feature 1 -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 text-center hover:shadow-lg transition duration-300">
                    <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6 text-3xl">🌱</div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Rumput Sintetis Standar FIFA</h3>
                    <p class="text-slate-500">Bermain lebih nyaman dan aman dengan kualitas rumput sintetis terbaik yang tebal dan empuk.</p>
                </div>
                <!-- Feature 2 -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 text-center hover:shadow-lg transition duration-300">
                    <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6 text-3xl">💡</div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Pencahayaan LED Terang</h3>
                    <p class="text-slate-500">Jadwal malam bukan masalah. Lapangan kami dilengkapi dengan lampu LED berstandar tinggi.</p>
                </div>
                <!-- Feature 3 -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 text-center hover:shadow-lg transition duration-300">
                    <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6 text-3xl">🚿</div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Fasilitas Lengkap</h3>
                    <p class="text-slate-500">Tersedia tribun penonton, ruang ganti bersih, toilet, kamar mandi shower, dan kantin.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-slate-900 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-slate-400">&copy; 2026 MiniSoccer Booking System. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
