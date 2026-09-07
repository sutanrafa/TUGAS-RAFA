<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit();
}
include "config/koneksi.php";

// Tambah Buku
if (isset($_POST['simpan'])) {
    $judul = $_POST['judul'];
    $penerbit = $_POST['penerbit'];
    $stok = $_POST['stok'];

    $stmt = mysqli_prepare($koneksi, "INSERT INTO buku (judul, penerbit, stok) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssi", $judul, $penerbit, $stok);
    mysqli_stmt_execute($stmt);
    header("Location: buku.php");
    exit();
}

// Hapus Buku
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = mysqli_prepare($koneksi, "DELETE FROM buku WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    header("Location: buku.php");
    exit();
}

$data = mysqli_query($koneksi, "SELECT * FROM buku ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Buku - Sistem Perpustakaan</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f6f9; }
        header { background: #2c3e50; color: white; padding: 15px 30px; }
        nav { background: #34495e; padding: 10px 30px; }
        nav a { margin-right: 20px; text-decoration: none; font-weight: bold; color: #ecf0f1; }
        .container { padding: 30px; }
        .box { background: white; padding: 20px; border-radius: 8px; width: 400px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 25px; }
        input { width: 100%; padding: 8px; margin: 6px 0 14px 0; box-sizing: border-box; }
        button { background: #3498db; color: white; padding: 10px 15px; border: none; cursor: pointer; border-radius: 4px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #34495e; color: white; }
        tr:hover { background: #f1f1f1; }
        .btn-delete { color: #e74c3c; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<header>
    <h2>Data Buku (Data Utama)</h2>
</header>

<nav>
    <a href="index.php">Dashboard</a>
    <a href="siswa.php">Data Siswa</a>
    <a href="buku.php">Data Buku</a>
    <a href="transaksi.php">Transaksi Peminjaman</a>
    <a href="logout.php" style="color: #e74c3c;">Logout</a>
</nav>

<div class="container">
    <div class="box">
        <h3>Tambah Data Buku</h3>
        <form method="POST">
            <label>Judul Buku</label>
            <input type="text" name="judul" required>

            <label>Penerbit</label>
            <input type="text" name="penerbit" required>

            <label>Jumlah Stok</label>
            <input type="number" name="stok" min="0" required>

            <button name="simpan">Simpan Buku</button>
        </form>
    </div>

    <h3>Daftar Buku</h3>
    <table>
        <tr>
            <th>No</th>
            <th>Judul Buku</th>
            <th>Penerbit</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
        <?php $no=1; while($d = mysqli_fetch_assoc($data)){ ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($d['judul']); ?></td>
            <td><?= htmlspecialchars($d['penerbit']); ?></td>
            <td><b><?= $d['stok']; ?></b></td>
            <td>
                <a href="buku.php?hapus=<?= $d['id']; ?>" class="btn-delete" onclick="return confirm('Yakin hapus data buku ini?')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>
