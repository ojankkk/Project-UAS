<?php
session_start();
include '../config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Cek email di database
    $query = $conn->query("SELECT * FROM member WHERE email = '$email'");
    $user = $query->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Simpan ke session
        $_SESSION['member'] = [
            'id' => $user['id'],
            'nama' => $user['nama'],
            'email' => $user['email']
        ];
        

        // Simpan aktivitas login
        $id_pelanggan = $user['id'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $conn->query("INSERT INTO log_login (id_pelanggan, ip_address) VALUES ($id_pelanggan, '$ip')");

        header("Location: index.php");
        exit;
    } else {
        $error = "Email atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Member</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <h3 class="text-center mb-4">Login Member</h3>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button class="btn btn-primary w-100">Login</button>
                <a href="signup.php" class="btn btn-link w-100 mt-2">Belum punya akun? Daftar</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
