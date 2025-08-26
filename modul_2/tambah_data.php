<html lang="id">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tambah Data Siswa</title>
</head>
<body>
    <h2>Formulir Tambah Data Siswa</h2>
    <form action="proses_tambah.php" method="POST">
        <laber for="nama">Nama:<label>
        <input type="text" id="nama" name="nama" required><br></br>

        <laber for="email">Email:<label>
        <input type="email" id="email" name="email" required><br></br>

        <laber for="alamat">Alamat:<label>
        <textarea id="alamat" name="alamat" required></textarea><br></br>

        <button type="submit">Tambah</button>
    </form>
</body>
</html>