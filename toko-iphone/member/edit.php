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

$data = $conn->query("SELECT * FROM member WHERE id = $id");
$user = $data->fetch_assoc();

$pesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $conn->real_escape_string($_POST['nama']);
    $password = $_POST['password'];

    // Jika password diisi, update password juga
    if (!empty($password)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $update = $conn->query("UPDATE member SET nama='$nama', password='$hash' WHERE id=$id");
    } else {
        $update = $conn->query("UPDATE member SET nama='$nama' WHERE id=$id");
    }

    if ($update) {
        // Update juga session
        $_SESSION['member']['nama'] = $nama;
        $pesan = '✅ Profil berhasil diperbarui.';
    } else {
        $pesan = '❌ Gagal menyimpan perubahan.';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h3 class="mb-4">✏️ Edit Profil</h3>
            
            <?php if ($pesan): ?>
                <div class="alert alert-info"><?= $pesan ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']) ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control" disabled>
                </div>
                <div class="mb-3">
                    <label>Password Baru <small>(kosongkan jika tidak ingin ganti)</small></label>
                    <input type="password" name="password" class="form-control">
                </div>
                <button class="btn btn-success w-100">💾 Simpan Perubahan</button>
                <a href="profile.php" class="btn btn-link w-100 mt-2">← Kembali ke Profil</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
