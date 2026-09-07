<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit();
}
include "config/koneksi.php";

if (isset($_GET['id'])) {
    $id_transaksi = $_GET['id'];
    $tgl_kembali  = date('Y-m-d');

    // 1. Ambil id_buku dari transaksi
    $query = mysqli_query($koneksi, "SELECT id_buku FROM peminjaman WHERE id='$id_transaksi'");
    $data  = mysqli_fetch_assoc($query);

    if ($data) {
        $id_buku = $data['id_buku'];

        // 2. Update status transaksi menjadi 'Dikembalikan'
        mysqli_query($koneksi, "UPDATE peminjaman SET status='Dikembalikan', tgl_kembali='$tgl_kembali' WHERE id='$id_transaksi'");

        // 3. Tambahkan kembali stok buku
        mysqli_query($koneksi, "UPDATE buku SET stok = stok + 1 WHERE id='$id_buku'");
    }
}

header("Location: transaksi.php");
exit();
?>
