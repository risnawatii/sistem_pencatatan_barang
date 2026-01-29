<?php
require 'auth.php';
require 'db.php';

if (!isset($_GET['id'])) {
    header('Location: products.php');
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: products.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $new_quantity = (int) $_POST['quantity'];
    $old_quantity = (int) $product['quantity'];

    if ($name === '' || $new_quantity < 0) {
        $error = 'Data tidak valid.';
    } else {

        // UPDATE PRODUCT
        $stmt = $pdo->prepare(
            'UPDATE products SET name = ?, quantity = ? WHERE id = ?'
        );

        if ($stmt->execute([$name, $new_quantity, $id])) {

            // HITUNG SELISIH (HANYA JIKA NAIK)
            if ($new_quantity > $old_quantity) {
                $tambah = $new_quantity - $old_quantity;

                // CATAT BARANG MASUK
                $stmt = $pdo->prepare(
                    "INSERT INTO stock_logs (product_id, quantity) VALUES (?, ?)"
                );
                $stmt->execute([$id, $tambah]);
            }

            header('Location: products.php');
            exit();

        } else {
            $error = 'Gagal memperbarui barang.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Barang</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-amber-50 min-h-screen flex items-center justify-center">

<div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">

  <h2 class="text-2xl font-bold text-amber-900 text-center mb-6">
    Edit Barang
  </h2>

  <?php if ($error): ?>
    <div class="mb-4 bg-red-50 text-red-600 text-sm px-4 py-2 rounded text-center">
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>

  <form method="post" class="space-y-4">
    <div>
      <label class="block text-sm font-semibold mb-1 text-amber-900">Nama Barang</label>
      <input type="text" name="name"
             value="<?= htmlspecialchars($product['name']); ?>" required
             class="w-full px-4 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500">
    </div>

    <div>
      <label class="block text-sm font-semibold mb-1 text-amber-900">Jumlah Barang</label>
      <input type="number" name="quantity" min="0"
             value="<?= htmlspecialchars($product['quantity']); ?>" required
             class="w-full px-4 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500">
    </div>

    <div class="flex justify-between pt-4">
      <a href="products.php" class="text-sm text-amber-700 hover:underline">← Kembali</a>
      <button class="bg-amber-700 hover:bg-amber-900 text-white px-6 py-2 rounded-lg">
        Perbarui
      </button>
    </div>
  </form>

</div>

</body>
</html>
