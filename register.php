<?php
// register.php
session_start();
require 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $role = 'admin';

    if ($username == '' || $password == '' || $name == '') {
        $error = 'Silakan lengkapi semua field yang diperlukan.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = 'Username sudah terdaftar.';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare('INSERT INTO users (username, password, name, phone, address, role) VALUES (?, ?, ?, ?, ?, ?)');
            if ($stmt->execute([$username, $hashedPassword, $name, $phone, $address, $role])) {
                $success = 'Registrasi berhasil! Admin baru telah ditambahkan.';
            } else {
                $error = 'Terjadi kesalahan saat mendaftar, silakan coba lagi.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registrasi Admin - Mini Market</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FFF8F1; /* amber soft */
            margin: 0; padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            max-width: 400px;
            width: 100%;
            background: #ffffff;
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgb(0 0 0 / 0.1);
            box-sizing: border-box;
            text-align: center;
        }
        h2 {
            margin-bottom: 8px;
            font-weight: 600;
            color: #B45309; /* amber-800 */
        }
        p.description {
            margin-bottom: 20px;
            color: #D97706; /* amber-600 */
            font-size: 0.9rem;
        }
        input, textarea {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 15px;
            border: 1.5px solid #FBBF24; /* amber-400 */
            border-radius: 6px;
            font-size: 1rem;
            box-sizing: border-box;
            transition: border-color 0.2s;
            font-family: inherit;
        }
        input:focus, textarea:focus {
            outline: none;
            border-color: #F59E0B; /* amber-500 */
            box-shadow: 0 0 6px #F59E0B66;
        }
        textarea {
            resize: vertical;
            min-height: 60px;
        }
        button {
            width: 100%;
            padding: 12px 0;
            background-color: #F59E0B; /* amber-500 */
            border: none;
            border-radius: 6px;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #D97706; /* amber-600 */
        }
        .message {
            margin-bottom: 15px;
            font-weight: 600;
        }
        .error {
            color: #B91C1C; /* merah */
        }
        .success {
            color: #15803D; /* hijau gelap */
        }
        .login-link {
            margin-top: 15px;
            font-size: 0.85rem;
            color: #92400E; /* amber-700 */
        }
        .login-link a {
            color: #F59E0B; /* amber-500 */
            font-weight: 600;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registrasi Admin Baru</h2>
        <p class="description">Lengkapi form berikut untuk membuat akun admin baru.</p>

        <?php if ($error): ?>
            <div class="message error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="message success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="post" action="register.php" autocomplete="off">
            <input type="text" name="username" placeholder="Username" required autofocus />
            <input type="password" name="password" placeholder="Password" required />
            <input type="text" name="name" placeholder="Nama Lengkap" required />
            <input type="text" name="phone" placeholder="Telepon (opsional)" />
            <textarea name="address" placeholder="Alamat (opsional)"></textarea>
            <button type="submit">Daftar</button>
        </form>

        <p class="login-link">
            Sudah punya akun? <a href="login.php">Login di sini</a>
        </p>
    </div>
</body>
</html>
