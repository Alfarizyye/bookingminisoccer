<?php
require 'config/db.php';
$stmt = $pdo->query("SELECT name, email, role FROM users");
$users = $stmt->fetchAll();
echo "Daftar User:\n";
foreach ($users as $u) {
    echo "Nama: " . $u['name'] . " | Email: " . $u['email'] . " | Role: " . $u['role'] . "\n";
}
?>
