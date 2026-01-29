<?php
// my_profile.php
require 'auth.php';
require 'db.php';

$user = $_SESSION['user'];

?>

<!DOCTYPE html>
<html>
<head>
    <title>Profil Saya - Mini Market</title>
</head>
<body>
    <h2>Profil Saya</h2>
    <nav>
        <a href="index.php">Dashboard</a> |
        <a href="products.php">Data Barang</a> |
        <?php if ($user['role'] === 'admin'): ?>
            <a href="employees.php">Data Karyawan/Kasir</a> |
        <?php endif; ?>
        <a href="logout.php">Logout</a>
    </nav>
    <hr>
    <p><strong>ID:</strong> <?php echo htmlspecialchars($user['id']); ?></p>
    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
    <p><strong>Nama:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
    <p><strong>Nomor Telepon:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
    <p><strong>Alamat:</strong> <?php echo nl2br(htmlspecialchars($user['address'])); ?></p>
</body>
</html>
