<?php
session_start();
require_once 'koneksi.php';
$error = ''; $success = '';
$activeTab = isset($_POST['register']) ? 'register' : 'login';

if (isset($_POST['login'])) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $_POST['username']);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: tiket.php");
        exit();
    }
    $error = "Username atau password salah!";
}

if (isset($_POST['register'])) {
    if ($_POST['reg_password'] != $_POST['confirm_password']) {
        $error = "Password tidak cocok!";
    } elseif (strlen($_POST['reg_password']) < 6) {
        $error = "Password minimal 6 karakter!";
    } else {
        $hashed = password_hash($_POST['reg_password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $_POST['reg_username'], $hashed, $_POST['email']);
        if ($stmt->execute()) {
            $success = "Registrasi berhasil! Silakan login.";
            $activeTab = 'login';
        } else { $error = "Username sudah digunakan!"; }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tiket Online</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --secondary: #4f46e5;
            --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        body { 
            font-family: 'Inter', sans-serif; 
            background: var(--bg-gradient); 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin: 0;
        }
        .container { width: 100%; max-width: 420px; padding: 20px; }
        .box { 
            background: rgba(255, 255, 255, 0.95); 
            backdrop-filter: blur(10px);
            border-radius: 24px; 
            padding: 40px; 
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
        }
        h3 { 
            text-align: center; 
            font-size: 26px;
            letter-spacing: -1px;
            color: #1e293b; 
            margin: 0 0 10px;
        }
        .subtitle {
            text-align: center;
            display: block;
            font-size: 14px;
            color: #64748b;
            margin-bottom: 30px;
        }
        .tab-group { 
            display: flex; 
            background: #f1f5f9; 
            padding: 6px; 
            border-radius: 16px; 
            margin-bottom: 30px; 
        }
        .tab-group button { 
            flex: 1; border: none; padding: 12px; cursor: pointer; border-radius: 12px; 
            background: transparent; font-weight: 600; color: #64748b; transition: 0.3s;
        }
        .tab-group button.active { 
            background: white; color: var(--primary); box-shadow: 0 4px 6px rgba(0,0,0,0.05); 
        }
        label { font-size: 14px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block; }
        input { 
            width: 100%; padding: 14px 16px; margin-bottom: 20px; border: 2px solid #f1f5f9; 
            border-radius: 12px; box-sizing: border-box; transition: 0.3s; background: #f8fafc;
        }
        input:focus { 
            outline: none; border-color: var(--primary); background: white;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1); 
        }
        button[type="submit"] { 
            width: 100%; background: var(--primary); color: white; border: none; 
            padding: 16px; border-radius: 12px; font-weight: 700; cursor: pointer; transition: 0.3s; 
        }
        button[type="submit"]:hover { 
            background: var(--secondary); transform: translateY(-2px); 
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4);
        }
        .error, .success { 
            padding: 14px; border-radius: 12px; margin-bottom: 25px; font-size: 14px; text-align: center; 
        }
        .error { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
        .success { background: #ecfdf5; color: #059669; border: 1px solid #d1fae5; }
        .pane { display: none; }
        .pane.active { display: block; animation: slideUp 0.4s ease-out; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>
<div class="container">
    <div class="box">
        <h3>TIKET ONLINE</h3>
        <span class="subtitle">Jakarta - Malaysia</span>
        
        <?php if($error) echo "<div class='error'>$error</div>"; ?>
        <?php if($success) echo "<div class='success'>$success</div>"; ?>
        
        <div class="tab-group">
            <button class="<?= $activeTab=='login' ? 'active' : '' ?>" onclick="showTab('login', event)">LOGIN</button>
            <button class="<?= $activeTab=='register' ? 'active' : '' ?>" onclick="showTab('register', event)">DAFTAR</button>
        </div>
        
        <div id="login" class="pane <?= $activeTab=='login' ? 'active' : '' ?>">
            <form method="post">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan username" required>
                <label>Password</label>
                <input type="password" name="password" placeholder="••••••••" required>
                <button type="submit" name="login">Masuk Sekarang</button>
            </form>
        </div>
        
        <div id="register" class="pane <?= $activeTab=='register' ? 'active' : '' ?>">
            <form method="post">
                <label>Username</label>
                <input type="text" name="reg_username" placeholder="Buat username" required>
                <label>Email (Opsional)</label>
                <input type="email" name="email" placeholder="nama@email.com">
                <label>Password</label>
                <input type="password" name="reg_password" placeholder="Min. 6 karakter" required>
                <label>Konfirmasi Password</label>
                <input type="password" name="confirm_password" placeholder="Ulangi password" required>
                <button type="submit" name="register">Buat Akun Baru</button>
            </form>
        </div>
    </div>
</div>

<script>
function showTab(tabId, event) {
    document.querySelectorAll('.pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tab-group button').forEach(b => b.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');
    event.currentTarget.classList.add('active');
}
</script>
</body>
</html>