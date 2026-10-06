<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Get all fields
$stmt = $pdo->query("SELECT * FROM fields");
$fields = $stmt->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $field_id = $_POST['field_id'];
    $booking_date = $_POST['booking_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Validate time (simple validation)
    if (strtotime($start_time) >= strtotime($end_time)) {
        $error = "Waktu selesai harus setelah waktu mulai.";
    } else {
        // Check availability
        $check = $pdo->prepare("SELECT id FROM bookings WHERE field_id = ? AND booking_date = ? AND status != 'cancelled' AND ((start_time < ? AND end_time > ?) OR (start_time < ? AND end_time > ?))");
        $check->execute([$field_id, $booking_date, $end_time, $start_time, $end_time, $start_time]);
        
        if ($check->rowCount() > 0) {
            $error = "Maaf, lapangan sudah dipesan pada rentang waktu tersebut.";
        } else {
            // Get field price
            $stmt = $pdo->prepare("SELECT price_per_hour FROM fields WHERE id = ?");
            $stmt->execute([$field_id]);
            $field = $stmt->fetch();
            
            // Calculate total price
            $start = strtotime($start_time);
            $end = strtotime($end_time);
            $hours = ($end - $start) / 3600;
            $total_price = $hours * $field['price_per_hour'];

            // Insert booking
            $insert = $pdo->prepare("INSERT INTO bookings (user_id, field_id, booking_date, start_time, end_time, total_price, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
            if ($insert->execute([$user_id, $field_id, $booking_date, $start_time, $end_time, $total_price])) {
                header("Location: riwayat.php?msg=booked");
                exit();
            } else {
                $error = "Terjadi kesalahan saat memesan.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking - Mini Soccer Booking</title>
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
                            <a href="booking.php" class="bg-emerald-50 text-emerald-700 px-3 py-2 rounded-md font-medium">Pesan Lapangan</a>
                            <a href="riwayat.php" class="text-slate-600 hover:text-emerald-600 px-3 py-2 rounded-md font-medium">Riwayat</a>
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
                <a href="booking.php" class="bg-emerald-50 text-emerald-700 block px-3 py-2 rounded-md text-base font-medium">Pesan Lapangan</a>
                <a href="riwayat.php" class="text-slate-600 hover:bg-slate-50 block px-3 py-2 rounded-md text-base font-medium">Riwayat</a>
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

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
            <h2 class="text-2xl font-bold text-slate-800 mb-6">Pesan Lapangan</h2>

            <?php if(isset($error)): ?>
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-5">
                    <label class="block text-slate-700 text-sm font-semibold mb-2">Pilih Lapangan</label>
                    <select name="field_id" required class="w-full px-4 py-3 rounded-lg bg-slate-50 border border-slate-200 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                        <option value="">-- Pilih Lapangan --</option>
                        <?php foreach($fields as $f): ?>
                            <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['name']) ?> - Rp <?= number_format($f['price_per_hour'], 0, ',', '.') ?>/jam</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-5">
                    <label class="block text-slate-700 text-sm font-semibold mb-2">Tanggal Main</label>
                    <input type="date" name="booking_date" required min="<?= date('Y-m-d') ?>" class="w-full px-4 py-3 rounded-lg bg-slate-50 border border-slate-200 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Jam Mulai</label>
                        <select name="start_time" required class="w-full px-4 py-3 rounded-lg bg-slate-50 border border-slate-200 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                            <?php for($i=6; $i<=23; $i++): $time = sprintf("%02d:00", $i); ?>
                                <option value="<?= $time ?>"><?= $time ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Jam Selesai</label>
                        <select name="end_time" required class="w-full px-4 py-3 rounded-lg bg-slate-50 border border-slate-200 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                            <?php for($i=7; $i<=24; $i++): $time = sprintf("%02d:00", $i == 24 ? 0 : $i); ?>
                                <option value="<?= $time ?>"><?= $time ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                    Konfirmasi Pesanan
                </button>
            </form>
        </div>
    </div>
</body>
</html>
