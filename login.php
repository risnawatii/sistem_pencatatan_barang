<?php
session_start();
require 'db.php';

// Jika sudah login, langsung ke index
if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();  
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username == '' || $password == '') {
        $error = 'Silakan masukkan username dan password.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password['password'] ?? $password, $user['password'])) {
            $_SESSION['user'] = $user;
            header('Location: index.php');
            exit();
        } else {
            $error = 'Username atau password salah.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Mini Market</title>
<link rel="stylesheet" href="style.css">
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #FFF8F1; /* amber soft */
        margin: 0;
        padding: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .container {
        width: 100%;
        max-width: 400px;
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        padding: 30px;
        text-align: center;
    }

    h2 {
        color: #B45309; /* amber-800 */
        margin-bottom: 10px;
    }

    p.welcome {
        color: #D97706; /* amber-600 */
        margin-bottom: 20px;
        font-size: 14px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        color: #92400E; /* amber-700 */
        text-align: left;
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #FBBF24; /* amber-400 */
        border-radius: 5px;
        box-sizing: border-box;
    }

    button {
        width: 100%;
        padding: 12px;
        background-color: #F59E0B; /* amber-500 */
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #D97706; /* amber-600 */
    }

    .error {
        color: #B91C1C; /* merah */
        text-align: center;
        margin-bottom: 15px;
    }

    .register-link {
        margin-top: 15px;
        font-size: 13px;
    }

    .register-link a {
        color: #F59E0B; /* amber-500 */
        text-decoration: none;
        font-weight: bold;
    }

    .register-link a:hover {
        text-decoration: underline;
    }

    .footer-msg {
        margin-top: 15px;
        font-size: 13px;
        color: #92400E; /* amber-700 */
    }
</style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <p class="welcome">Selamat datang di Sistem Pencatatan Barang Mini Market</p>
        
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error); ?></p>
        <?php endif; ?>
        
        <form method="post" action="login.php">
            <label>Username:</label>
            <input type="text" name="username" placeholder="Masukkan username" autofocus required>
            
            <label>Password:</label>
            <input type="password" name="password" placeholder="Masukkan password" required>
            
            <button type="submit">Login</button>
        </form>

        <p class="register-link">
            Belum punya akun? <a href="register.php">Daftar di sini</a>
        </p>

        <p class="footer-msg">© <?= date('Y'); ?> Mini Market</p>
    </div>
</body>
</html>
