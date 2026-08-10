<?php
session_start();
include "koneksi.php";

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Mencari user berdasarkan username dan password
    $query = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username' AND password='$password'");
    $cek = mysqli_num_rows($query);

    if($cek > 0){
        $_SESSION['username'] = $username;
        $_SESSION['status'] = "login";
        header("Location: index.php");
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login System</title>
    <style>
        body {
            font-family: Arial;
            margin: 40px;
            background: #f5f5f5;
        }

        .box {
            width: 350px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 100px auto;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }

        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 8px 20px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
        }

        .alert {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Form Login</h2>

    <?php if(isset($error)){ ?>
        <div class="alert"><?= $error; ?></div>
    <?php } ?>

    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button name="login">Login</button>
    </form>
</div>

</body>
</html>