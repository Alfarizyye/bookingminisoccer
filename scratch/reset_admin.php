<?php
require 'config/db.php';
$email = 'admin@minisoccer.com';
$new_password = password_hash('admin123', PASSWORD_DEFAULT);
$stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ? AND role = 'admin'");
if ($stmt->execute([$new_password, $email])) {
    echo "Password admin berhasil direset menjadi: admin123\n";
} else {
    echo "Gagal meriset password.\n";
}
?>
