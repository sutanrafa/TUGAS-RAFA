<?php
include "koneksi.php";

//Tambah Data
if(isset($_POST['simpan'])){
    $nisn = $_POST['nisn'];
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];

    mysqli_query($koneksi,"INSERT INTO siswa(nisn,nama,kelas)
    VALUES('$nisn','$nama','$kelas')");
}

$data = mysqli_query($koneksi,"SELECT * FROM siswa");
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Siswa</title>

    <style>
        body{
            font-family: Arial;
            margin:40px;
            background:#f5f5f5;
        }

        .box{
            width:500px;
            background:white;
            padding:20px;
            border-radius:8px;
        }

        input{
            width:100%;
            padding:8px;
            margin-top:5px;
            margin-bottom:10px;
        }

        button{
            padding:8px 20px;
            cursor:pointer;
        }

        table{
            width:100%;
            margin-top:25px;
            border-collapse:collapse;
            background:white;
        }

        table,th,td{
            border:1px solid black;
        }

        th,td{
            padding:8px;
            text-align:center;
        }

        a{
            text-decoration:none;
        }
    </style>

</head>
<body>

<div class="box">

<h2>Data Siswa</h2>

<form method="POST">

<label>NISN</label>
<input type="text" name="nisn" required>

<label>Nama</label>
<input type="text" name="nama" required>

<label>Kelas</label>
<input type="text" name="kelas" required>

<button name="simpan">Simpan</button>

</form>

</div>

<table>

<tr>
    <th>No</th>
    <th>NISN</th>
    <th>Nama</th>
    <th>Kelas</th>
    <th>Aksi</th>
</tr>

<?php
$no=1;
while($d=mysqli_fetch_array($data)){
?>

<tr>

<td><?= $no++; ?></td>

<td><?= $d['nisn']; ?></td>

<td><?= $d['nama']; ?></td>

<td><?= $d['kelas']; ?></td>

<td>
<a href="edit.php?id=<?= $d['id']; ?>">Edit</a> |
<a href="hapus.php?id=<?= $d['id']; ?>" onclick="return confirm('Hapus data?')">Hapus</a>
</td>

</tr>

<?php } ?>

</table>

</body>
</html>