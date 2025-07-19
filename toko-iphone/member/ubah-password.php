<?php
session_start();
include '../config.php';

// Cek login
if (!isset($_SESSION['member'])) {
    header("Location: login.php");
    exit;
}

$member = $_SESSION['member'];
$id = $member['id'];

$pesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lama = $_POST['password_lama'];
    $baru = $_POST['password_baru'];
    $konfirmasi = $_POST['konfirmasi'];

    // Ambil data member dari DB
    $query = $conn->query("SELECT * FROM member WHERE id = $id");
    $user = $query->fetch_assoc();

    if (!password_verify($lama, $user['password'])) {
        $pesan = '<div class="alert alert-danger">❌ Password lama salah!</div>';
    } elseif ($baru !== $konfirmasi) {
        $pesan = '<div class="alert alert-warning">⚠️ Password baru tidak cocok!</div>';
    } else {
        $hash = password_hash($baru, PASSWORD_DEFAULT);
        $conn->query("UPDATE member SET password='$hash' WHERE id = $id");
        $pesan = '<div class="alert alert-success">✅ Password berhasil diubah!</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ubah Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h3 class="mb-4">🔑 Ubah Password</h3>

            <?= $pesan ?>

            <form method="post">
                <div class="mb-3">
                    <label>Password Lama</label>
                    <input type="password" name="password_lama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password Baru</label>
                    <input type="password" name="password_baru" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Konfirmasi Password Baru</label>
                    <input type="password" name="konfirmasi" class="form-control" required>
                </div>
                <button class="btn btn-success w-100">Simpan Password Baru</button>
                <a href="profile.php" class="btn btn-link w-100 mt-2">← Kembali ke Profil</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
