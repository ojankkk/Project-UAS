<?php
include '../config.php';

$error = '';
$sukses = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi'];

    if ($password !== $konfirmasi) {
        $error = "Password dan konfirmasi tidak cocok!";
    } else {
        // Cek apakah username sudah ada
        $cek = $conn->query("SELECT * FROM admin WHERE username = '$username'");
        if ($cek->num_rows > 0) {
            $error = "Username sudah terdaftar!";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO admin (username, password) VALUES ('$username', '$hash')";
            if ($conn->query($sql)) {
                $sukses = "Admin berhasil didaftarkan! Silakan login.";
            } else {
                $error = "Gagal daftar admin.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sign Up Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <h3 class="text-center mb-4">Daftar Admin Baru</h3>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php elseif ($sukses): ?>
                <div class="alert alert-success"><?= $sukses ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="konfirmasi" class="form-control" required>
                </div>
                <button class="btn btn-primary w-100">Daftar</button>
                <a href="login.php" class="btn btn-link w-100 mt-2">Kembali ke Login</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
