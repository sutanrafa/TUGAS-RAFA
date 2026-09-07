<?php
session_start();
include 'config/koneksi.php'; // Jika koneksi.php ada di dalam folder config

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];

// Handle Tambah Peminjaman Baru
if (isset($_POST['tambah_peminjaman']) && $role == 'admin') {
    $id_siswa = $_POST['id_siswa'];
    $id_buku = $_POST['id_buku'];
    $tgl_pinjam = $_POST['tgl_pinjam'];
    // Default jatuh tempo 7 hari dari tanggal pinjam
    $tgl_jatuh_tempo = date('Y-m-d', strtotime($tgl_pinjam . ' + 7 days'));

    // Cek stok buku
    $cek_stok = mysqli_query($koneksi, "SELECT stok FROM buku WHERE id='$id_buku'");
    $stok_buku = mysqli_fetch_assoc($cek_stok)['stok'];

    if ($stok_buku > 0) {
        // Simpan transaksi
        $stmt = $koneksi->prepare("INSERT INTO peminjaman (id_siswa, id_buku, tgl_pinjam, tgl_jatuh_tempo, status) VALUES (?, ?, ?, ?, 'Dipinjam')");
        $stmt->bind_param("iiss", $id_siswa, $id_buku, $tgl_pinjam, $tgl_jatuh_tempo);
        $stmt->execute();

        // Kurangi stok
        $update_stok = $koneksi->prepare("UPDATE buku SET stok = stok - 1 WHERE id=?");
        $update_stok->bind_param("i", $id_buku);
        $update_stok->execute();

        header("Location: transaksi.php");
        exit();
    } else {
        echo "<script>alert('Stok buku habis!'); window.location='transaksi.php';</script>";
    }
}

// Handle Update / Edit Transaksi
if (isset($_POST['update_transaksi']) && $role == 'admin') {
    $id_transaksi = $_POST['id_transaksi'];
    $tgl_pinjam = $_POST['tgl_pinjam'];
    $tgl_jatuh_tempo = $_POST['tgl_jatuh_tempo'];
    $status = $_POST['status'];

    $stmt = $koneksi->prepare("UPDATE peminjaman SET tgl_pinjam=?, tgl_jatuh_tempo=?, status=? WHERE id=?");
    $stmt->bind_param("sssi", $tgl_pinjam, $tgl_jatuh_tempo, $status, $id_transaksi);
    $stmt->execute();

    header("Location: transaksi.php");
    exit();
}

// Query mengambil data peminjaman
$query = "SELECT peminjaman.*, siswa.nama AS nama_siswa, buku.judul AS judul_buku 
          FROM peminjaman 
          JOIN siswa ON peminjaman.id_siswa = siswa.id 
          JOIN buku ON peminjaman.id_buku = buku.id 
          ORDER BY peminjaman.id DESC";
$result = mysqli_query($koneksi, $query);

// Data untuk Form Tambah
$siswa_option = mysqli_query($koneksi, "SELECT * FROM siswa");
$buku_option = mysqli_query($koneksi, "SELECT * FROM buku WHERE stok > 0");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi Peminjaman - Perpustakaan</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f9; }
        .nav { margin-bottom: 20px; }
        .nav a { margin-right: 15px; text-decoration: none; color: #333; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; background: #fff; margin-top: 15px; }
        table, th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #007bff; color: white; }
        .btn { padding: 5px 10px; text-decoration: none; color: white; border-radius: 3px; display: inline-block; font-size: 13px; cursor: pointer; border: none; }
        .btn-green { background-color: #28a745; }
        .btn-blue { background-color: #17a2b8; }
        .btn-warning { background-color: #ffc107; color: #000; }
        .card { background: white; padding: 15px; border-radius: 5px; margin-bottom: 20px; box-shadow: 0 0 5px rgba(0,0,0,0.1); }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
        .modal-content { background: white; width: 400px; margin: 100px auto; padding: 20px; border-radius: 5px; }
    </style>
</head>
<body>

    <div class="nav">
        <a href="index.php">Dashboard</a>
        <a href="siswa.php">Data Siswa</a>
        <a href="buku.php">Data Buku</a>
        <a href="transaksi.php">Transaksi Peminjaman</a>
        <a href="logout.php" style="color:red;">Logout</a>
    </div>

    <h2>Daftar Transaksi Peminjaman</h2>

    <?php if ($role == 'admin'): ?>
    <div class="card">
        <h3>Tambah Transaksi Peminjaman</h3>
        <form action="" method="POST">
            <p>
                <label>Siswa:</label><br>
                <select name="id_siswa" required style="width:100%; padding:8px;">
                    <option value="">-- Pilih Siswa --</option>
                    <?php while ($s = mysqli_fetch_assoc($siswa_option)): ?>
                        <option value="<?= $s['id']; ?>"><?= $s['nama']; ?> (<?= $s['nisn']; ?>)</option>
                    <?php endwhile; ?>
                </select>
            </p>
            <p>
                <label>Buku:</label><br>
                <select name="id_buku" required style="width:100%; padding:8px;">
                    <option value="">-- Pilih Buku --</option>
                    <?php while ($b = mysqli_fetch_assoc($buku_option)): ?>
                        <option value="<?= $b['id']; ?>"><?= $b['judul']; ?> (Stok: <?= $b['stok']; ?>)</option>
                    <?php endwhile; ?>
                </select>
            </p>
            <p>
                <label>Tanggal Pinjam:</label><br>
                <input type="date" name="tgl_pinjam" value="<?= date('Y-m-d'); ?>" required style="width:100%; padding:8px;">
            </p>
            <small style="color: gray;">*Jatuh tempo otomatis diset 7 hari setelah tanggal pinjam.</small><br><br>
            <button type="submit" name="tambah_peminjaman" class="btn btn-green">Simpan Peminjaman</button>
        </form>
    </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Jatuh Tempo</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <?php if ($role == 'admin'): ?><th>Aksi</th><?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($row['nama_siswa']); ?></td>
                <td><?= htmlspecialchars($row['judul_buku']); ?></td>
                <td><?= $row['tgl_pinjam']; ?></td>
                <td><b><?= $row['tgl_jatuh_tempo'] ? $row['tgl_jatuh_tempo'] : '-'; ?></b></td>
                <td><?= $row['tgl_kembali'] ? $row['tgl_kembali'] : '-'; ?></td>
                <td>
                    <?php if ($row['status'] == 'Dipinjam'): ?>
                        <span style="color: orange; font-weight: bold;">Dipinjam</span>
                    <?php else: ?>
                        <span style="color: green; font-weight: bold;">Dikembalikan</span>
                    <?php endif; ?>
                </td>
                <?php if ($role == 'admin'): ?>
                <td>
                    <?php if ($row['status'] == 'Dipinjam'): ?>
                        <a href="kembalikan.php?id=<?= $row['id']; ?>" class="btn btn-green" onclick="return confirm('Proses pengembalian buku?')">Kembalikan</a>
                    <?php endif; ?>
                    
                    <button class="btn btn-warning" onclick="openEditModal(<?= $row['id']; ?>, '<?= $row['tgl_pinjam']; ?>', '<?= $row['tgl_jatuh_tempo']; ?>', '<?= $row['status']; ?>')">Edit</button>
                </td>
                <?php endif; ?>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Modal Form Edit Transaksi -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h3>Edit Transaksi Peminjaman</h3>
            <form action="" method="POST">
                <input type="hidden" name="id_transaksi" id="edit_id">
                <p>
                    <label>Tanggal Pinjam:</label><br>
                    <input type="date" name="tgl_pinjam" id="edit_tgl_pinjam" required style="width:100%; padding:8px;">
                </p>
                <p>
                    <label>Tanggal Jatuh Tempo:</label><br>
                    <input type="date" name="tgl_jatuh_tempo" id="edit_tgl_jatuh_tempo" required style="width:100%; padding:8px;">
                </p>
                <p>
                    <label>Status:</label><br>
                    <select name="status" id="edit_status" style="width:100%; padding:8px;">
                        <option value="Dipinjam">Dipinjam</option>
                        <option value="Dikembalikan">Dikembalikan</option>
                    </select>
                </p>
                <button type="submit" name="update_transaksi" class="btn btn-blue">Update Transaksi</button>
                <button type="button" class="btn" style="background:#6c757d;" onclick="closeEditModal()">Batal</button>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, tglPinjam, tglJatuhTempo, status) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_tgl_pinjam').value = tglPinjam;
            document.getElementById('edit_tgl_jatuh_tempo').value = tglJatuhTempo;
            document.getElementById('edit_status').value = status;
            document.getElementById('editModal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }
    </script>
</body>
</html>