<?php

require 'auth.php';
require 'db.php';

if (!isset($_GET['id'])) {
    header('Location: products.php');
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
$stmt->execute([$id]);

header('Location: products.php');
exit();
?>
