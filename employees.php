<?php
session_start();
require 'db.php';

/* =====================
   CEK LOGIN
===================== */
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

/* =====================
   AMBIL DATA KARYAWAN
===================== */
$stmt = $pdo->query("
    SELECT id, username, name, phone, role
    FROM users
    ORDER BY id DESC
");
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Karyawan</title>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">

<style>
body {
  font-family: 'Plus Jakarta Sans', sans-serif;
  background-color: #FFF8F1;
}
</style>
</head>

<body class="min-h-screen">

<!-- HEADER -->
<header class="bg-gradient-to-r from-amber-700 to-amber-900 text-white px-6 py-5">
  <div class="max-w-6xl mx-auto flex items-center gap-4">
    <img src="images/6.png" class="w-8 h-8" alt="">
    <div>
      <h1 class="font-bold">Sistem Pencatatan Barang</h1>
      <p class="text-sm text-amber-300">PT. AnandaRia</p>
    </div>
  </div>
</header>

<!-- NAV -->
<nav class="bg-white shadow">
  <div class="max-w-6xl mx-auto px-6 py-3 flex gap-2 items-center">

    <a href="index.php"
       class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-amber-100">
      <img src="images/7.png" class="w-5 h-5">
      Dashboard
    </a>

    <a href="products.php"
       class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-amber-100">
      <img src="images/8.png" class="w-5 h-5">
      Data Barang
    </a>

    <a href="employees.php"
       class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-sm bg-amber-200 text-amber-900">
      <img src="images/14.png" class="w-5 h-5">
      Data Karyawan
    </a>

    <span class="ml-auto text-amber-700 font-semibold">
      👋 <?= htmlspecialchars($_SESSION['user']['name']) ?>
    </span>
    <a href="logout.php" class="ml-4 text-red-500 text-sm hover:underline">Logout</a>
  </div>
</nav>

<!-- CONTENT -->
<main class="max-w-6xl mx-auto px-6 py-8">

<!-- TITLE -->
<div class="bg-gradient-to-r from-amber-200 to-amber-100 rounded-2xl p-8 shadow flex justify-between items-center mb-6">
  <div>
    <h2 class="text-3xl font-bold text-amber-900 mb-2">
      Data Karyawan
    </h2>
    <p class="text-amber-800">
      Daftar seluruh akun pengguna sistem
    </p>
  </div>
  <img src="images/11.png" class="w-24 opacity-80">
</div>

<!-- TABLE -->
<div class="overflow-x-auto bg-white rounded-2xl shadow">
<table class="min-w-full text-sm">
<thead class="bg-amber-100 text-amber-900">
<tr>
  <th class="px-4 py-3 text-left">ID</th>
  <th class="px-4 py-3 text-left">Username</th>
  <th class="px-4 py-3 text-left">Nama</th>
  <th class="px-4 py-3 text-left">Telepon</th>
  <th class="px-4 py-3 text-left">Role</th>
  <th class="px-4 py-3 text-center">Aksi</th>
</tr>
</thead>

<tbody class="divide-y">
<?php if ($employees): ?>
<?php foreach ($employees as $e): ?>
<tr class="hover:bg-amber-50">
  <td class="px-4 py-3"><?= $e['id'] ?></td>
  <td class="px-4 py-3"><?= htmlspecialchars($e['username']) ?></td>
  <td class="px-4 py-3"><?= htmlspecialchars($e['name']) ?></td>
  <td class="px-4 py-3"><?= htmlspecialchars($e['phone']) ?></td>
  <td class="px-4 py-3">
    <?php if ($e['role'] === 'admin'): ?>
      <span class="px-3 py-1 rounded-full text-xs bg-red-100 text-red-700 font-semibold">Admin</span>
    <?php else: ?>
      <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-700 font-semibold">Cashier</span>
    <?php endif; ?>
  </td>

  <!-- AKSI -->
  <td class="px-4 py-3 text-center">
    <div class="flex justify-center gap-3">

      <a href="view_employee.php?id=<?= $e['id'] ?>"
         class="bg-amber-100 hover:bg-amber-200 p-2 rounded-xl"
         title="Detail">
        <img src="images/14.png" class="w-5 h-5">
      </a>

      <a href="edit_employee.php?id=<?= $e['id'] ?>"
         class="bg-blue-100 hover:bg-blue-200 p-2 rounded-xl"
         title="Edit">
        <img src="images/11.png" class="w-5 h-5">
      </a>

      <a href="delete_employee.php?id=<?= $e['id'] ?>"
         onclick="return confirm('Yakin hapus data?')"
         class="bg-red-100 hover:bg-red-200 p-2 rounded-xl"
         title="Hapus">
        <img src="images/10.png" class="w-5 h-5">
      </a>

    </div>
  </td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr>
  <td colspan="6" class="px-6 py-6 text-center text-gray-500">
    Data karyawan belum tersedia
  </td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>

</main>
</body>
</html>
