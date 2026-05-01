<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get all bookings for this user
$stmt = $pdo->prepare("SELECT b.*, f.name as field_name FROM bookings b JOIN fields f ON b.field_id = f.id WHERE b.user_id = ? ORDER BY b.created_at DESC");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat - Mini Soccer Booking</title>
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
                            <a href="riwayat.php" class="bg-emerald-50 text-emerald-700 px-3 py-2 rounded-md font-medium">Riwayat</a>
                            <a href="teman.php" class="text-slate-600 hover:text-emerald-600 px-3 py-2 rounded-md font-medium">Cari Teman</a>
                        </div>
                    </div>
                </div>
                <div class="hidden md:flex items-center">
                    <span class="text-slate-600 mr-4">Halo, <?= htmlspecialchars($_SESSION['user_name']) ?>!</span>
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
                <a href="riwayat.php" class="bg-emerald-50 text-emerald-700 block px-3 py-2 rounded-md text-base font-medium">Riwayat</a>
                <a href="teman.php" class="text-slate-600 hover:bg-slate-50 block px-3 py-2 rounded-md text-base font-medium">Cari Teman</a>
            </div>
            <div class="pt-4 pb-3 border-t border-slate-200">
                <div class="flex items-center px-5">
                    <div class="ml-3">
                        <div class="text-base font-medium text-slate-800">Halo, <?= htmlspecialchars($_SESSION['user_name']) ?>!</div>
                    </div>
                </div>
                <div class="mt-3 px-2 space-y-1">
                    <a href="../auth/logout.php" class="block px-3 py-2 rounded-md text-base font-medium text-red-600 hover:text-red-800 hover:bg-red-50">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h2 class="text-3xl font-bold text-slate-800 mb-6">Riwayat Pemesanan</h2>

        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'booked'): ?>
            <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-6 rounded shadow-sm">
                Pesanan berhasil dibuat! Silakan lakukan pembayaran jika status masih Pending.
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Lapangan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal & Waktu</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Harga</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        <?php if (count($bookings) > 0): ?>
                            <?php foreach ($bookings as $b): ?>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                        #<?= str_pad($b['id'], 4, '0', STR_PAD_LEFT) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-slate-900"><?= htmlspecialchars($b['field_name']) ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                        <?= date('d M Y', strtotime($b['booking_date'])) ?><br>
                                        <?= date('H:i', strtotime($b['start_time'])) ?> - <?= date('H:i', strtotime($b['end_time'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                                        Rp <?= number_format($b['total_price'], 0, ',', '.') ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php 
                                            $status_class = 'bg-yellow-100 text-yellow-800'; // pending
                                            if ($b['status'] == 'paid' || $b['status'] == 'completed') $status_class = 'bg-emerald-100 text-emerald-800';
                                            if ($b['status'] == 'cancelled') $status_class = 'bg-red-100 text-red-800';
                                        ?>
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full <?= $status_class ?>">
                                            <?= ucfirst($b['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <?php if ($b['status'] == 'pending'): ?>
                                            <a href="payment.php?id=<?= $b['id'] ?>" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded-md transition">Bayar Sekarang</a>
                                        <?php else: ?>
                                            <span class="text-slate-400">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                    Belum ada riwayat pesanan.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
