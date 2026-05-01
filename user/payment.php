<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: riwayat.php");
    exit();
}

$booking_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Get booking details
$stmt = $pdo->prepare("SELECT b.*, f.name as field_name FROM bookings b JOIN fields f ON b.field_id = f.id WHERE b.id = ? AND b.user_id = ? AND b.status = 'pending'");
$stmt->execute([$booking_id, $user_id]);
$booking = $stmt->fetch();

if (!$booking) {
    header("Location: riwayat.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle file upload
    $target_dir = "../assets/uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($_FILES["proof"]["name"], PATHINFO_EXTENSION));
    $new_filename = "proof_" . $booking_id . "_" . time() . "." . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    $allowed_types = ['jpg', 'jpeg', 'png'];
    
    if (in_array($file_extension, $allowed_types)) {
        if (move_uploaded_file($_FILES["proof"]["tmp_name"], $target_file)) {
            // Insert into payments
            $stmt = $pdo->prepare("INSERT INTO payments (booking_id, proof_image) VALUES (?, ?)");
            $stmt->execute([$booking_id, $new_filename]);
            
            // Update booking status to 'paid' (or you could use 'awaiting_verification')
            $stmt = $pdo->prepare("UPDATE bookings SET status = 'paid' WHERE id = ?");
            $stmt->execute([$booking_id]);
            
            header("Location: riwayat.php?msg=paid");
            exit();
        } else {
            $error = "Terjadi kesalahan saat mengunggah file.";
        }
    } else {
        $error = "Hanya file JPG, JPEG, & PNG yang diperbolehkan.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Mini Soccer Booking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50">
    <div class="max-w-2xl mx-auto px-4 py-10">
        <a href="riwayat.php" class="text-emerald-600 hover:text-emerald-800 font-medium mb-6 inline-block">&larr; Kembali ke Riwayat</a>
        
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
            <h2 class="text-2xl font-bold text-slate-800 mb-6">Pembayaran Pesanan #<?= str_pad($booking_id, 4, '0', STR_PAD_LEFT) ?></h2>

            <div class="bg-slate-50 p-6 rounded-xl border border-slate-200 mb-8">
                <p class="text-sm text-slate-500 mb-1">Total Tagihan:</p>
                <p class="text-3xl font-bold text-slate-900 mb-4">Rp <?= number_format($booking['total_price'], 0, ',', '.') ?></p>
                
                <p class="text-sm text-slate-500 mb-1">Silakan transfer ke salah satu rekening berikut:</p>
                <div class="bg-white p-4 rounded-lg border border-slate-200 mt-2 flex justify-between items-center">
                    <div>
                        <p class="font-bold text-slate-800">BCA - 1234567890</p>
                        <p class="text-sm text-slate-500">a.n. Mini Soccer Resmi</p>
                    </div>
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/2560px-Bank_Central_Asia.svg.png" class="h-6 object-contain" alt="BCA">
                </div>
            </div>

            <?php if(isset($error)): ?>
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data">
                <div class="mb-6">
                    <label class="block text-slate-700 text-sm font-semibold mb-2">Unggah Bukti Pembayaran (JPG/PNG)</label>
                    <input type="file" name="proof" required accept="image/*" class="w-full px-4 py-3 rounded-lg bg-slate-50 border border-slate-200 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-200 outline-none transition-all cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                </div>

                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                    Konfirmasi Pembayaran
                </button>
            </form>
        </div>
    </div>
</body>
</html>
