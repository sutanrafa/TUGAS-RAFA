<?php
session_start();
include "config/koneksi.php";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepared Statement untuk mencegah SQL Injection
    $stmt = mysqli_prepare($koneksi, "SELECT id, username, password, role FROM user WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password']) || $password === $row['password']) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['status'] = "login";
            header("Location: index.php");
            exit();
        }
    }
    $error = "Username atau Password salah!";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login System - Perpustakaan</title>
    <style>
        body { font-family: Arial, sans-serif; background: #eef2f3; margin: 0; padding: 0; }
        .box { width: 340px; background: white; padding: 30px; margin: 100px auto; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 20px; color: #333; }
        label { font-weight: bold; font-size: 14px; color: #555; }
        input, button { width: 100%; padding: 10px; margin: 8px 0 16px 0; box-sizing: border-box; border-radius: 4px; border: 1px solid #ccc; }
        button { background: #4CAF50; color: white; font-weight: bold; border: none; cursor: pointer; transition: 0.3s; }
        button:hover { background: #45a049; }
        .alert { color: red; font-size: 14px; text-align: center; }
    </style>
</head>
<body>
<div class="box">
    <h2>Login Sistem</h2>
    <?php if(isset($error)) echo "<p class='alert'>$error</p>"; ?>
    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" placeholder="Masukkan username" required>
        
        <label>Password</label>
        <input type="password" name="password" placeholder="Masukkan password" required>
        
        <button name="login">Login</button>
    </form>
</div>
</body>
</html>
