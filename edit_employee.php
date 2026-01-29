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

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name    = trim($_POST['name']);
    $phone   = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if ($name == '') {
        $error = 'Silakan isi nama karyawan.';
    } else {
        $stmt = $pdo->prepare('UPDATE users SET name = ?, phone = ?, address = ? WHERE id = ?');
        if ($stmt->execute([$name, $phone, $address, $id])) {
            header('Location: employees.php');
            exit();
        } else {
            $error = 'Gagal memperbarui data karyawan.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Karyawan/Kasir - Mini Market</title>
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
    color: #A16207;
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

form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

label {
    font-weight: bold;
    color: #92400E; /* medium amber */
}

input[type="text"],
textarea {
    padding: 10px;
    border: 1px solid #FCD34D; /* soft amber border */
    border-radius: 6px;
    width: 100%;
    box-sizing: border-box;
}

textarea {
    resize: vertical;
    min-height: 80px;
}

button {
    padding: 12px;
    background-color: #A16207; /* dark amber */
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    transition: 0.3s;
}

button:hover {
    background-color: #78350F; /* darker amber */
}

.error-message {
    color: #B91C1C; /* red for errors */
    text-align: center;
}

</style>
</head>
<body>
<div class="container">
    <h2>Edit Karyawan/Kasir</h2>
    <nav>
        <a href="employees.php">Kembali</a>
        <a href="index.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </nav>
    <hr>

    <?php if ($error): ?>
        <p class="error-message"><?= htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="post" action="edit_employee.php?id=<?= $id; ?>">
        <label>Nama Karyawan:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($employee['name']); ?>" required>

        <label>Nomor Telepon:</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($employee['phone']); ?>" required>

        <label>Alamat:</label>
        <textarea name="address" required><?= htmlspecialchars($employee['address']); ?></textarea>

        <button type="submit">Perbarui</button>
    </form>
</div>
</body>
</html>
