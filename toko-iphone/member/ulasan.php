<?php
session_start();
include '../config.php';

// Cek apakah member sudah login
if (!isset($_SESSION['member'])) {
    header("Location: login.php");
    exit;
}

$id_pelanggan = $_SESSION['member']['id'];

if (!isset($_GET['id'])) {
    echo "ID produk tidak ditemukan.";
    exit;
}

$id_produk = (int)$_GET['id'];
$produk = $conn->query("SELECT * FROM produk WHERE id = $id_produk")->fetch_assoc();

if (!$produk) {
    echo "Produk tidak ditemukan.";
    exit;
}

// Proses form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rating   = (int)$_POST['rating'];
    $komentar = trim($_POST['komentar']);

    $stmt = $conn->prepare("INSERT INTO ulasan (id_produk, id_pelanggan, rating, komentar, tanggal) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("iiis", $id_produk, $id_pelanggan, $rating, $komentar);
    $stmt->execute();

    header("Location: ../produk.php?id=" . $id_produk);
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beri Ulasan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <a href="../produk.php?id=<?= $id_produk ?>" class="btn btn-secondary mb-3">← Kembali ke Produk</a>
    <h2>Ulasan untuk: <?= htmlspecialchars($produk['nama']) ?></h2>

    <form method="POST">
        <div class="mb-3">
            <label for="rating" class="form-label">Rating (1-5)</label>
            <select name="rating" id="rating" class="form-select" required>
                <option value="">-- Pilih Rating --</option>
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <option value="<?= $i ?>"><?= $i ?> Bintang</option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="komentar" class="form-label">Komentar</label>
            <textarea name="komentar" id="komentar" class="form-control" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
    </form>
</div>
</body>
</html>
