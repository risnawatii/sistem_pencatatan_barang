<?php
require 'auth.php';
require 'db.php';

$products = $pdo->query('SELECT * FROM products')->fetchAll();

// Hitung total stok keseluruhan
$total_stok = 0;
foreach ($products as $p) {
    $total_stok += (int)$p['quantity'];
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Data Barang</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-amber-50">

<!-- HEADER -->
<header class="bg-gradient-to-r from-amber-700 to-amber-900 text-white px-6 py-5">
  <div class="max-w-6xl mx-auto flex items-center gap-4">
    <div class="w-10 h-10 flex items-center justify-center">
        <img src="images/logo.png" alt="Welcome" class="w-8 h-8 object-contain">
    </div>
    <div>
      <h1 class="font-bold">Sistem Pencatatan Barang</h1>
      <p class="text-sm text-amber-300">PT. AnandaRia</p>
    </div>
  </div>
</header>

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
    <?php if(isset($_SESSION['user'])): ?>
      <span class="ml-auto text-amber-700 font-semibold">
        👋 <?= htmlspecialchars($_SESSION['user']['name']) ?>
      </span>
      <a href="logout.php" class="ml-4 text-red-500 text-sm hover:underline">Logout</a>
    <?php endif; ?>
  </div>
</nav>


<main class="max-w-6xl mx-auto px-6 py-6">

<!-- CARD STAT -->
<div class="grid grid-cols-2 gap-4 mb-6">

  <!-- JUMLAH BARANG -->
  <div class="bg-white p-5 rounded-xl shadow flex items-center gap-4">
    <img src="images/12.png" alt="Jumlah Barang" class="w-8 h-8">
    <div>
      <p class="text-sm text-amber-600">Jumlah Barang</p>
      <p class="text-2xl font-bold text-amber-900"><?= count($products); ?></p>
    </div>
  </div>

  <!-- TOTAL STOK -->
  <div class="bg-white p-5 rounded-xl shadow flex items-center gap-4">
    <img src="images/8.png" alt="Total Stok" class="w-8 h-8">
    <div>
      <p class="text-sm text-amber-600">Total Stok Keseluruhan</p>
      <p class="text-2xl font-bold text-amber-900"><?= $total_stok; ?></p>
    </div>
  </div>

</div>

<div class="flex justify-end mb-4">
  <a href="add_product.php"
     class="bg-amber-700 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-amber-900 transition">
     + Tambah Barang
  </a>
</div>

<!-- TABLE -->
<div class="bg-white rounded-xl shadow overflow-hidden">
  <table class="w-full">
    <thead class="bg-amber-100 text-left text-sm text-amber-800">
      <tr>
        <th class="p-4">ID</th>
        <th>Nama</th>
        <th>Jumlah</th>
        <th class="text-center">Aksi</th>
      </tr>
    </thead>

    <tbody>
      <?php if (!$products): ?>
        <tr>
          <td colspan="4" class="text-center p-6 text-amber-400">
            Belum ada data barang
          </td>
        </tr>
      <?php endif; ?>

      <?php foreach ($products as $p): ?>
      <tr class="border-t hover:bg-amber-50">
        <td class="p-4 text-amber-900"><?= htmlspecialchars($p['id']); ?></td>
        <td class="text-amber-900"><?= htmlspecialchars($p['name']); ?></td>
        <td class="text-amber-900"><?= htmlspecialchars($p['quantity']); ?></td>
        <td class="p-4">
          <div class="flex gap-2 justify-center">
            <!-- Tombol Edit dengan ikon -->
            <a href="edit_product.php?id=<?= $p['id']; ?>"
               class="flex items-center gap-1 px-3 py-1 rounded-lg bg-amber-200 text-amber-900 hover:bg-amber-900 hover:text-white active:bg-amber-700 active:text-white transition"
               title="Edit">
              <img src="images/11.png" alt="Edit" class="w-4 h-4">
              <span>Edit</span>
            </a>

            <!-- Tombol Hapus dengan ikon -->
            <a href="delete_product.php?id=<?= $p['id']; ?>"
               onclick="return confirm('Hapus?')"
               class="flex items-center gap-1 px-3 py-1 rounded-lg bg-amber-200 text-amber-900 hover:bg-red-600 hover:text-white transition"
               title="Hapus">
              <img src="images/10.png" alt="Hapus" class="w-4 h-4">
              <span>Hapus</span>
            </a>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

</main>
</body>
</html>
