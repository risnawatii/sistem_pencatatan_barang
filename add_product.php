<?php
require 'auth.php';
require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $quantity = trim($_POST['quantity'] ?? '');

    if ($name === '' || $quantity === '') {
        $error = 'Silakan isi semua field.';
    } elseif (!is_numeric($quantity) || $quantity < 0) {
        $error = 'Jumlah harus berupa angka non-negatif.';
    } else {

        // INSERT PRODUCT
        $stmt = $pdo->prepare(
            "INSERT INTO products (name, quantity) VALUES (?, ?)"
        );

        if ($stmt->execute([$name, $quantity])) {

            // ambil ID produk baru
            $product_id = $pdo->lastInsertId();

            // CATAT BARANG MASUK
            $stmt = $pdo->prepare(
                "INSERT INTO stock_logs (product_id, quantity) VALUES (?, ?)"
            );
            $stmt->execute([$product_id, $quantity]);

            header('Location: products.php');
            exit;

        } else {
            $error = 'Gagal menambahkan barang.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Tambah Barang</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-amber-50 min-h-screen flex items-center justify-center">

<div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">

  <h2 class="text-2xl font-bold text-amber-900 text-center mb-6">
    Tambah Barang
  </h2>

  <?php if ($error): ?>
    <div class="mb-4 bg-red-50 text-red-600 text-sm px-4 py-2 rounded text-center">
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>

  <form method="post" class="space-y-4">
    <div>
      <label class="block text-sm font-semibold mb-1 text-amber-900">Nama Barang</label>
      <input type="text" name="name" required
             class="w-full px-4 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500">
    </div>

    <div>
      <label class="block text-sm font-semibold mb-1 text-amber-900">Jumlah Barang</label>
      <input type="number" name="quantity" min="0" required
             class="w-full px-4 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500">
    </div>

    <div class="flex justify-between pt-4">
      <a href="products.php" class="text-sm text-amber-700 hover:underline">← Kembali</a>
      <button class="bg-amber-700 hover:bg-amber-900 text-white px-6 py-2 rounded-lg">
        Simpan
      </button>
    </div>
  </form>

</div>

</body>
</html>
