<?php
session_start();
require '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Check if email exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $error = "Email sudah terdaftar!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, phone) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$name, $email, $password, $phone])) {
            header("Location: login.php?msg=registered");
            exit();
        } else {
            $error = "Terjadi kesalahan, coba lagi.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Mini Soccer Booking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md border border-slate-100">
        <h2 class="text-3xl font-bold text-center text-slate-800 mb-2">Daftar Akun</h2>
        <p class="text-center text-slate-500 mb-8">Bergabunglah untuk mulai memesan lapangan</p>
        
        <?php if(isset($error)): ?>
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm" role="alert">
                <p><?= $error ?></p>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-4">
                <label class="block text-slate-700 text-sm font-semibold mb-2">Nama Lengkap</label>
                <input type="text" name="name" required class="w-full px-4 py-3 rounded-lg bg-slate-50 border border-slate-200 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
            </div>
            <div class="mb-4">
                <label class="block text-slate-700 text-sm font-semibold mb-2">Email</label>
                <input type="email" name="email" required class="w-full px-4 py-3 rounded-lg bg-slate-50 border border-slate-200 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
            </div>
            <div class="mb-4">
                <label class="block text-slate-700 text-sm font-semibold mb-2">Nomor HP</label>
                <input type="text" name="phone" required class="w-full px-4 py-3 rounded-lg bg-slate-50 border border-slate-200 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
            </div>
            <div class="mb-6">
                <label class="block text-slate-700 text-sm font-semibold mb-2">Password</label>
                <input type="password" name="password" required class="w-full px-4 py-3 rounded-lg bg-slate-50 border border-slate-200 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
            </div>
            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                Daftar Sekarang
            </button>
            <p class="text-center text-sm text-slate-600 mt-6">
                Sudah punya akun? <a href="login.php" class="text-emerald-600 hover:text-emerald-800 font-semibold transition">Masuk di sini</a>
            </p>
            <p class="text-center mt-4">
                <a href="../index.php" class="text-slate-400 hover:text-slate-600 text-sm">Kembali ke Beranda</a>
            </p>
        </form>
    </div>
</body>
</html>
