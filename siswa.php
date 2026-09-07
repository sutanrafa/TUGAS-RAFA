<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit();
}
include "config/koneksi.php";

// Tambah Siswa
if (isset($_POST['simpan'])) {
    $nisn = $_POST['nisn'];
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];

    $stmt = mysqli_prepare($koneksi, "INSERT INTO siswa (nisn, nama, kelas) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $nisn, $nama, $kelas);
    mysqli_stmt_execute($stmt);
    header("Location: siswa.php");
    exit();
}

// Hapus Siswa
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = mysqli_prepare($koneksi, "DELETE FROM siswa WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    header("Location: siswa.php");
    exit();
}

$data = mysqli_query($koneksi, "SELECT * FROM siswa ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Siswa - Sistem Perpustakaan</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f6f9; }
        header { background: #2c3e50; color: white; padding: 15px 30px; }
        nav { background: #34495e; padding: 10px 30px; }
        nav a { margin-right: 20px; text-decoration: none; font-weight: bold; color: #ecf0f1; }
        .container { padding: 30px; }
        .box { background: white; padding: 20px; border-radius: 8px; width: 400px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 25px; }
        input { width: 100%; padding: 8px; margin: 6px 0 14px 0; box-sizing: border-box; }
        button { background: #2ecc71; color: white; padding: 10px 15px; border: none; cursor: pointer; border-radius: 4px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #34495e; color: white; }
        tr:hover { background: #f1f1f1; }
        .btn-delete { color: #e74c3c; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<header>
    <h2>Data Siswa (Data Pendukung)</h2>
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
        <h3>Tambah Data Siswa</h3>
        <form method="POST">
            <label>NISN</label>
            <input type="text" name="nisn" required>

            <label>Nama Siswa</label>
            <input type="text" name="nama" required>

            <label>Kelas</label>
            <input type="text" name="kelas" required>

            <button name="simpan">Simpan Siswa</button>
        </form>
    </div>

    <h3>Daftar Siswa</h3>
    <table>
        <tr>
            <th>No</th>
            <th>NISN</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Aksi</th>
        </tr>
        <?php $no=1; while($d = mysqli_fetch_assoc($data)){ ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($d['nisn']); ?></td>
            <td><?= htmlspecialchars($d['nama']); ?></td>
            <td><?= htmlspecialchars($d['kelas']); ?></td>
            <td>
                <a href="siswa.php?hapus=<?= $d['id']; ?>" class="btn-delete" onclick="return confirm('Yakin hapus data siswa ini?')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>
