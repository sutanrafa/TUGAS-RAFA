<?php
include "koneksi.php";

$id=$_GET['id'];

$data=mysqli_query($koneksi,"SELECT * FROM siswa WHERE id='$id'");
$d=mysqli_fetch_array($data);

if(isset($_POST['update'])){

$nisn=$_POST['nisn'];
$nama=$_POST['nama'];
$kelas=$_POST['kelas'];

mysqli_query($koneksi,"UPDATE siswa SET
nisn='$nisn',
nama='$nama',
kelas='$kelas'
WHERE id='$id'");

header("Location:index.php");

}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Siswa</title>
</head>

<body>

<h2>Edit Data</h2>

<form method="POST">

NISN
<br>
<input type="text" name="nisn" value="<?= $d['nisn']; ?>">

<br><br>

Nama
<br>
<input type="text" name="nama" value="<?= $d['nama']; ?>">

<br><br>

Kelas
<br>
<input type="text" name="kelas" value="<?= $d['kelas']; ?>">

<br><br>

<button name="update">Update</button>

</form>

</body>
</html>