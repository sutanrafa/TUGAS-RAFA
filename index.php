<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit();
}
include "config/koneksi.php";

$tot_siswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa"));
$tot_buku  = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM buku"));
$tot_pinjam = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE status='Dipinjam'"));
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Sistem Informasi Perpustakaan</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f6f9; color: #333; }
        header { background: #2c3e50; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        nav { background: #34495e; padding: 10px 30px; }
        nav a { margin-right: 20px; text-decoration: none; font-weight: bold; color: #ecf0f1; }
        nav a:hover { color: #3498db; }
        .container { padding: 30px; }
        .cards { display: flex; gap: 20px; margin-top: 20px; }
        .card { flex: 1; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); text-align: center; border-left: 5px solid #3498db; }
        .card h3 { margin: 0 0 10px 0; font-size: 16px; color: #7f8c8d; }
        .card p { font-size: 32px; font-weight: bold; margin: 0; color: #2c3e50; }
    </style>
</head>
<body>

<header>
    <h2>Sistem Informasi Perpustakaan</h2>
    <div>Halo, <b><?= $_SESSION['username']; ?></b> (<?= strtoupper($_SESSION['role']); ?>)</div>
</header>

<nav>
    <a href="index.php">Dashboard</a>
    <a href="siswa.php">Data Siswa</a>
    <a href="buku.php">Data Buku</a>
    <a href="transaksi.php">Transaksi Peminjaman</a>
    <a href="logout.php" style="color: #e74c3c;">Logout</a>
</nav>

<div class="container">
    <h2>Dashboard Ringkasan</h2>
    <div class="cards">
        <div class="card" style="border-color: #3498db;">
            <h3>Total Siswa</h3>
            <p><?= $tot_siswa; ?></p>
        </div>
        <div class="card" style="border-color: #2ecc71;">
            <h3>Total Judul Buku</h3>
            <p><?= $tot_buku; ?></p>
        </div>
        <div class="card" style="border-color: #e67e22;">
            <h3>Buku Sedang Dipinjam</h3>
            <p><?= $tot_pinjam; ?></p>
        </div>
    </div>
</div>

</body>
</html>
