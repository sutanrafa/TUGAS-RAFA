<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$harga = [
    'GRD' => ['nama' => 'Garuda Indonesia', 'Eksekutif' => 1500000, 'Bisnis' => 900000, 'Ekonomi' => 500000],
    'MPT' => ['nama' => 'Merpati Air', 'Eksekutif' => 1200000, 'Bisnis' => 800000, 'Ekonomi' => 400000],
    'BTV' => ['nama' => 'Batavia Air', 'Eksekutif' => 1000000, 'Bisnis' => 700000, 'Ekonomi' => 300000]
];

$result = null;
if (isset($_POST['simpan'])) {
    $kode = $_POST['kode'];
    $kelas = $_POST['kelas'];
    $jumlah = (int)$_POST['jumlah'];
    $hargaTiket = $harga[$kode][$kelas];
    $result = [
        'nama' => $_POST['nama'],
        'namaPesawat' => $harga[$kode]['nama'],
        'kelas' => $kelas,
        'harga' => $hargaTiket,
        'jumlah' => $jumlah,
        'total' => $hargaTiket * $jumlah
    ];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pemesanan Tiket - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #4f46e5; --bg: #f8fafc; }
        body { 
            font-family: 'Inter', sans-serif; background: var(--bg); color: #1e293b; 
            margin: 0; padding: 40px 20px; 
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 24px 24px;
        }
        .card { 
            max-width: 550px; background: white; margin: 0 auto 30px; padding: 40px; 
            border-radius: 24px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
            border: 1px solid #f1f5f9; 
        }
        .header { 
            display: flex; justify-content: space-between; align-items: center; 
            margin-bottom: 30px; border-bottom: 1px solid #f1f5f9; padding-bottom: 20px;
        }
        h3 { margin: 0; color: var(--primary); font-weight: 800; font-size: 22px; }
        .user-tag { font-size: 13px; background: #eef2ff; color: var(--primary); padding: 6px 16px; border-radius: 20px; font-weight: 600; }
        .logout-link { color: #ef4444; text-decoration: none; font-size: 14px; font-weight: 600; }
        
        table { width: 100%; border-spacing: 0 15px; }
        label { display: block; font-size: 14px; font-weight: 600; color: #64748b; margin-bottom: 8px; }
        input[type="text"], select { 
            width: 100%; padding: 12px 16px; border-radius: 12px; border: 2px solid #f1f5f9; 
            background: #f8fafc; font-size: 15px; transition: 0.3s; box-sizing: border-box;
        }
        input:focus, select:focus { outline: none; border-color: var(--primary); background: white; }
        
        .radio-group { display: flex; gap: 10px; flex-wrap: wrap; }
        .radio-item { 
            background: #f1f5f9; padding: 10px 15px; border-radius: 10px; font-size: 13px; 
            cursor: pointer; display: flex; align-items: center; font-weight: 500; transition: 0.2s;
        }
        .radio-item:hover { background: #e2e8f0; }
        .radio-item input { margin-right: 8px; }

        button { 
            width: 100%; background: var(--primary); color: white; border: none; padding: 16px; 
            border-radius: 12px; cursor: pointer; font-weight: 700; font-size: 16px; transition: 0.3s; margin-top: 20px;
        }
        button:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3); }

        .result-item { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f8fafc; }
        .result-item span { color: #64748b; }
        .total-box { 
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: white;
            padding: 25px; border-radius: 20px; margin-top: 25px; text-align: center;
        }
    </style>
</head>
<body>

<div class="card">
    <div class="header">
        <span class="user-tag">Hi, <?= $_SESSION['username'] ?> 👋</span>
        <a href="logout.php" class="logout-link">Keluar</a>
    </div>
    <h3>Pemesanan Tiket</h3>
    <form method="post">
        <div style="margin-top: 20px;">
            <label>Nama Penumpang</label>
            <input type="text" name="nama" required placeholder="Masukkan nama lengkap">
        </div>
        
        <div style="margin-top: 20px;">
            <label>Pilih Maskapai</label>
            <select name="kode">
                <option value="GRD">Garuda Indonesia</option>
                <option value="MPT">Merpati Air</option>
                <option value="BTV">Batavia Air</option>
            </select>
        </div>

        <div style="margin-top: 20px;">
            <label>Kelas Penerbangan</label>
            <div class="radio-group">
                <label class="radio-item"><input type="radio" name="kelas" value="Eksekutif" checked> Eksekutif</label>
                <label class="radio-item"><input type="radio" name="kelas" value="Bisnis"> Bisnis</label>
                <label class="radio-item"><input type="radio" name="kelas" value="Ekonomi"> Ekonomi</label>
            </div>
        </div>

        <div style="margin-top: 20px;">
            <label>Jumlah Tiket</label>
            <select name="jumlah">
                <?php for($i=1;$i<=5;$i++) echo "<option>$i</option>"; ?>
            </select>
        </div>

        <button type="submit" name="simpan">Proses Pemesanan</button>
    </form>
</div>

<?php if ($result): ?>
<div class="card" style="border-top: 4px solid var(--primary); animation: slideUp 0.5s ease;">
    <h3 style="margin-bottom: 20px;">Detail Tiket Anda</h3>
    <div class="result-item"><span>Nama Penumpang</span> <strong><?= $result['nama'] ?></strong></div>
    <div class="result-item"><span>Maskapai</span> <strong><?= $result['namaPesawat'] ?></strong></div>
    <div class="result-item"><span>Kelas</span> <strong><?= $result['kelas'] ?></strong></div>
    <div class="result-item"><span>Harga Satuan</span> <strong>Rp <?= number_format($result['harga'],0,',','.') ?></strong></div>
    <div class="result-item"><span>Jumlah</span> <strong><?= $result['jumlah'] ?> Tiket</strong></div>
    
    <div class="total-box">
        <div style="font-size: 12px; text-transform: uppercase; letter-spacing: 1px; opacity: 0.9; margin-bottom: 5px;">Total Pembayaran</div>
        <div style="font-size: 28px; font-weight: 800;">Rp <?= number_format($result['total'],0,',','.') ?></div>
    </div>
</div>
<?php endif; ?>

</body>
</html>