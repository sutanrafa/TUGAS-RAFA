<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_sekolah");

if(!$koneksi){
    die("Koneksi Gagal");
}
?>