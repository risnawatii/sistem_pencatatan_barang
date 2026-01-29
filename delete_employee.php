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


if ($id == $user['id']) {
    echo "Anda tidak dapat menghapus diri sendiri.";
    exit();
}

$stmt = $pdo->prepare('DELETE FROM users WHERE id = ? AND role = "cashier"');
$stmt->execute([$id]);

header('Location: employees.php');
exit();
?>
