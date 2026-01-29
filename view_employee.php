<?php
require 'auth.php';
require 'db.php';

$user = $_SESSION['user'];

if ($user['role'] !== 'admin') {
    echo "Anda tidak memiliki izin untuk mengakses halaman ini.";
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: employees.php');
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ? AND role = "cashier"');
$stmt->execute([$id]);
$employee = $stmt->fetch();

if (!$employee) {
    header('Location: employees.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Karyawan/Kasir - Mini Market</title>
<link rel="stylesheet" href="style.css">
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #FFF8F1; /* soft amber */
    margin: 0;
    padding: 20px;
}

.container {
    max-width: 600px;
    margin: 0 auto;
    background-color: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    padding: 25px;
}

h2 {
    color: #A16207; /* dark amber */
    text-align: center;
    margin-bottom: 20px;
}

nav {
    margin-bottom: 20px;
    display: flex;
    justify-content: center;
    gap: 15px;
}

nav a {
    text-decoration: none;
    color: #A16207; /* dark amber */
    padding: 8px 12px;
    border-radius: 6px;
    transition: 0.3s;
}

nav a:hover {
    background-color: #A16207;
    color: white;
}

hr {
    border: 0;
    height: 1px;
    background: #FFD8A8; /* soft amber line */
    margin-bottom: 20px;
}

.detail-wrapper {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    font-size: 16px;
    color: #92400E; /* medium amber */
}

.detail-row .label {
    font-weight: bold;
}

.detail-row .value {
    text-align: right;
    word-break: break-word;
}
</style>
</head>
<body>
<div class="container">
    <h2>Detail Karyawan/Kasir</h2>
    <nav>
        <a href="employees.php">Kembali</a>
        <a href="index.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </nav>
    <hr>

    <div class="detail-wrapper">
        <div class="detail-row">
            <div class="label">ID Karyawan:</div>
            <div class="value"><?= htmlspecialchars($employee['id']); ?></div>
        </div>
        <div class="detail-row">
            <div class="label">Nama Karyawan:</div>
            <div class="value"><?= htmlspecialchars($employee['name']); ?></div>
        </div>
        <div class="detail-row">
            <div class="label">Nomor Telepon:</div>
            <div class="value"><?= htmlspecialchars($employee['phone']); ?></div>
        </div>
        <div class="detail-row">
            <div class="label">Alamat:</div>
            <div class="value"><?= nl2br(htmlspecialchars($employee['address'])); ?></div>
        </div>
    </div>
</div>
</body>
</html>
