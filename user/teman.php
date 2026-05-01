<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Handle new post submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_post'])) {
    $title = $_POST['title'];
    $play_date = $_POST['play_date'];
    $play_time = $_POST['play_time'];
    $location = $_POST['location'];
    $slot_needed = $_POST['slot_needed'];
    $contact_info = $_POST['contact_info'];

    $stmt = $pdo->prepare("INSERT INTO mabar_posts (user_id, title, play_date, play_time, location, slot_needed, contact_info) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$user_id, $title, $play_date, $play_time, $location, $slot_needed, $contact_info])) {
        $msg = "Pengumuman berhasil diposting!";
    } else {
        $error = "Gagal memposting pengumuman.";
    }
}

// Fetch all posts
$stmt = $pdo->query("SELECT m.*, u.name as poster_name FROM mabar_posts m JOIN users u ON m.user_id = u.id WHERE m.play_date >= CURDATE() ORDER BY m.created_at DESC");
$posts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Teman - Mini Soccer Booking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="font-bold text-xl text-emerald-600">⚽ MiniSoccer</span>
                    <div class="hidden md:block ml-10">
                        <div class="flex space-x-4">
                            <a href="dashboard.php" class="text-slate-600 hover:text-emerald-600 px-3 py-2 rounded-md font-medium">Dashboard</a>
                            <a href="booking.php" class="text-slate-600 hover:text-emerald-600 px-3 py-2 rounded-md font-medium">Pesan Lapangan</a>
                            <a href="riwayat.php" class="text-slate-600 hover:text-emerald-600 px-3 py-2 rounded-md font-medium">Riwayat</a>
                            <a href="teman.php" class="bg-emerald-50 text-emerald-700 px-3 py-2 rounded-md font-medium">Cari Teman</a>
                        </div>
                    </div>
                </div>
                <div class="hidden md:flex items-center">
                    <span class="text-slate-600 mr-4">Halo, <?= htmlspecialchars($user_name) ?>!</span>
                    <a href="../auth/logout.php" class="bg-red-50 text-red-600 hover:bg-red-100 px-4 py-2 rounded-lg font-medium transition">Logout</a>
                </div>
                <!-- Mobile menu button -->
                <div class="flex items-center md:hidden">
                    <button type="button" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-emerald-500" aria-expanded="false">
                        <span class="sr-only">Buka menu utama</span>
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="hidden md:hidden border-t border-slate-200 bg-white absolute w-full z-50 shadow-lg" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="dashboard.php" class="text-slate-600 hover:bg-slate-50 block px-3 py-2 rounded-md text-base font-medium">Dashboard</a>
                <a href="booking.php" class="text-slate-600 hover:bg-slate-50 block px-3 py-2 rounded-md text-base font-medium">Pesan Lapangan</a>
                <a href="riwayat.php" class="text-slate-600 hover:bg-slate-50 block px-3 py-2 rounded-md text-base font-medium">Riwayat</a>
                <a href="teman.php" class="bg-emerald-50 text-emerald-700 block px-3 py-2 rounded-md text-base font-medium">Cari Teman</a>
            </div>
            <div class="pt-4 pb-3 border-t border-slate-200">
                <div class="flex items-center px-5">
                    <div class="ml-3">
                        <div class="text-base font-medium text-slate-800">Halo, <?= htmlspecialchars($user_name) ?>!</div>
                    </div>
                </div>
                <div class="mt-3 px-2 space-y-1">
                    <a href="../auth/logout.php" class="block px-3 py-2 rounded-md text-base font-medium text-red-600 hover:text-red-800 hover:bg-red-50">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Papan Mabar</h1>
                <p class="text-slate-500 mt-1">Cari teman bermain atau umumkan slot kosong di tim Anda.</p>
            </div>
        </div>

        <?php if(isset($msg)): ?>
            <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-6 rounded shadow-sm">
                <?= $msg ?>
            </div>
        <?php endif; ?>
        <?php if(isset($error)): ?>
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Kolom Form Buat Post -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 sticky top-6">
                    <h3 class="text-xl font-bold text-slate-800 mb-4">Buat Pengumuman</h3>
                    <form method="POST" action="">
                        <input type="hidden" name="submit_post" value="1">
                        <div class="mb-4">
                            <label class="block text-slate-700 text-sm font-semibold mb-2">Judul (Singkat)</label>
                            <input type="text" name="title" required placeholder="Cth: Butuh 2 orang kiper" class="w-full px-4 py-2 rounded-lg bg-slate-50 border border-slate-200 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                        </div>
                        <div class="mb-4">
                            <label class="block text-slate-700 text-sm font-semibold mb-2">Tanggal Main</label>
                            <input type="date" name="play_date" required min="<?= date('Y-m-d') ?>" class="w-full px-4 py-2 rounded-lg bg-slate-50 border border-slate-200 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-2">Jam Main</label>
                                <input type="time" name="play_time" required class="w-full px-4 py-2 rounded-lg bg-slate-50 border border-slate-200 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-2">Butuh (Orang)</label>
                                <input type="number" name="slot_needed" required min="1" max="15" class="w-full px-4 py-2 rounded-lg bg-slate-50 border border-slate-200 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-slate-700 text-sm font-semibold mb-2">Lokasi / Lapangan</label>
                            <input type="text" name="location" required placeholder="Cth: Lapangan 1" class="w-full px-4 py-2 rounded-lg bg-slate-50 border border-slate-200 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                        </div>
                        <div class="mb-6">
                            <label class="block text-slate-700 text-sm font-semibold mb-2">Kontak WA / Line</label>
                            <input type="text" name="contact_info" required placeholder="08123xxxx" class="w-full px-4 py-2 rounded-lg bg-slate-50 border border-slate-200 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                        </div>
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-lg shadow-sm transition-all duration-200">
                            Posting Mabar
                        </button>
                    </form>
                </div>
            </div>

            <!-- Kolom Daftar Post -->
            <div class="lg:col-span-2">
                <div class="space-y-4">
                    <?php if(count($posts) > 0): ?>
                        <?php foreach($posts as $p): ?>
                            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition duration-300">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-xl font-bold text-slate-900"><?= htmlspecialchars($p['title']) ?></h3>
                                        <p class="text-sm text-slate-500 mt-1">Diposting oleh <span class="font-semibold text-slate-700"><?= htmlspecialchars($p['poster_name']) ?></span></p>
                                    </div>
                                    <div class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full text-sm font-bold border border-indigo-100">
                                        Butuh <?= $p['slot_needed'] ?> Orang
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                    <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                                        <p class="text-xs text-slate-500 mb-1">Tanggal</p>
                                        <p class="font-semibold text-slate-800 text-sm"><?= date('d M Y', strtotime($p['play_date'])) ?></p>
                                    </div>
                                    <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                                        <p class="text-xs text-slate-500 mb-1">Jam</p>
                                        <p class="font-semibold text-slate-800 text-sm"><?= date('H:i', strtotime($p['play_time'])) ?></p>
                                    </div>
                                    <div class="bg-slate-50 p-3 rounded-lg border border-slate-100 md:col-span-2">
                                        <p class="text-xs text-slate-500 mb-1">Lokasi</p>
                                        <p class="font-semibold text-slate-800 text-sm"><?= htmlspecialchars($p['location']) ?></p>
                                    </div>
                                </div>
                                <div class="flex items-center pt-4 border-t border-slate-100">
                                    <span class="bg-emerald-100 text-emerald-800 px-3 py-1.5 rounded-lg text-sm font-semibold flex items-center">
                                        <span class="mr-2">📱</span> <?= htmlspecialchars($p['contact_info']) ?>
                                    </span>
                                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $p['contact_info']) ?>" target="_blank" class="ml-4 text-sm font-medium text-emerald-600 hover:text-emerald-800">Hubungi &rarr;</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="bg-white p-12 rounded-2xl shadow-sm border border-slate-200 text-center">
                            <div class="text-4xl mb-4">🤷‍♂️</div>
                            <h3 class="text-xl font-bold text-slate-800 mb-2">Belum ada pengumuman</h3>
                            <p class="text-slate-500">Jadilah yang pertama mencari teman bermain!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
