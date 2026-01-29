<?php
require 'auth.php';
require 'db.php';

$user = $_SESSION['user'];

// hanya admin boleh akses
if ($user['role'] !== 'admin') {
    echo "Anda tidak memiliki izin untuk mengakses halaman ini.";
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $name     = trim($_POST['name']);
    $phone    = trim($_POST['phone']);
    $address  = trim($_POST['address']);
    $role     = 'cashier'; // 🔴 INI PENTING

    if ($username === '' || $password === '' || $name === '') {
        $error = 'Silakan isi semua field yang wajib.';
    } else {
        // cek username
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->fetch()) {
            $error = 'Username sudah digunakan.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // ✅ INSERT YANG BENAR (SEMUA DI-BIND)
            $stmt = $pdo->prepare(
                "INSERT INTO users (username, password, name, phone, address, role)
                 VALUES (?, ?, ?, ?, ?, ?)"
            );

            $success = $stmt->execute([
                $username,
                $hashed_password,
                $name,
                $phone,
                $address,
                $role
            ]);

            if ($success) {
                header("Location: employees.php");
                exit();
            } else {
                $error = 'Gagal menambahkan karyawan.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Karyawan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<h2>Tambah Karyawan / Kasir</h2>

<?php if ($error): ?>
    <p style="color:red"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="post">
    <label>Username</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password</label><br>
    <input type="password" name="password" required><br><br>

    <label>Nama</label><br>
    <input type="text" name="name" required><br><br>

    <label>Telepon</label><br>
    <input type="text" name="phone"><br><br>

    <label>Alamat</label><br>
    <textarea name="address"></textarea><br><br>

    <button type="submit">Tambah</button>
</form>

</body>
</html>
