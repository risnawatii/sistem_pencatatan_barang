<?php
session_start();
require 'db.php';

/* =====================
   STATISTIK BARANG
===================== */

// total jenis barang
$totalBarang = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();

// total stok keseluruhan
$totalStok = $pdo->query("SELECT SUM(quantity) FROM products")->fetchColumn();

// total barang masuk (dari log)
$totalMasuk = $pdo->query("SELECT SUM(quantity) FROM stock_logs")->fetchColumn();

// DATA GRAFIK barang masuk per tanggal
$chart = $pdo->query("
    SELECT DATE(created_at) AS tanggal, SUM(quantity) AS total
    FROM stock_logs
    GROUP BY DATE(created_at)
    ORDER BY tanggal ASC
")->fetchAll(PDO::FETCH_ASSOC);

$tanggal = [];
$total = [];

foreach ($chart as $c) {
    $tanggal[] = $c['tanggal'];
    $total[] = $c['total'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistem Pencatatan Barang</title>

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">

<style>
body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #FFF8F1; }
</style>
</head>

<body class="bg-amber-50 min-h-screen">

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
        👋 <?= htmlspecialchars($_SESSION['user']['name']); ?>
      </span>
      <a href="logout.php" class="ml-4 text-red-500 text-sm hover:underline">Logout</a>
    <?php else: ?>
      <a href="login.php" class="ml-auto text-amber-700 font-semibold">Login</a>
    <?php endif; ?>
  </div>
</nav>

<!-- CONTENT -->
<main class="max-w-6xl mx-auto px-6 py-8 space-y-8">

<!-- WELCOME -->
<div class="bg-gradient-to-r from-amber-200 to-amber-100 rounded-2xl p-8 shadow flex justify-between">
  <div>
    <h2 class="text-3xl font-bold text-amber-900 mb-2">
      Selamat Datang 👋
    </h2>
    <p class="text-amber-800">
      Dashboard Sistem Pencatatan Barang
    </p>
  </div>
  <img src="images/11.png" class="w-24 opacity-80">
</div>

<!-- STAT -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

  <div class="bg-white rounded-2xl shadow p-6">
    <p class="text-sm text-amber-600">Total Jenis Barang</p>
    <h3 class="text-3xl font-bold text-amber-800 mt-2">
      <?= $totalBarang ?? 0 ?>
    </h3>
  </div>

  <div class="bg-white rounded-2xl shadow p-6">
    <p class="text-sm text-green-600">Total Barang Masuk</p>
    <h3 class="text-3xl font-bold text-green-700 mt-2">
      <?= $totalMasuk ?? 0 ?>
    </h3>
  </div>

  <div class="bg-white rounded-2xl shadow p-6">
    <p class="text-sm text-amber-700">Total Stok Saat Ini</p>
    <h3 class="text-3xl font-bold text-amber-900 mt-2">
      <?= $totalStok ?? 0 ?>
    </h3>
  </div>

</div>

<!-- GRAFIK -->
<div class="bg-white rounded-2xl shadow p-6">
  <h4 class="font-bold text-amber-800 mb-4">
    📊 Grafik Barang Masuk
  </h4>
  <canvas id="chartBarangMasuk" height="90"></canvas>
</div>

</main>

<script>
new Chart(document.getElementById('chartBarangMasuk'), {
    type: 'line',
    data: {
        labels: <?= json_encode($tanggal) ?>,
        datasets: [{
            label: 'Barang Masuk',
            data: <?= json_encode($total) ?>,
            borderWidth: 3,
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>

</body>
</html>
