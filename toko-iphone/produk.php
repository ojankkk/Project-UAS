<?php
session_start();
include 'config.php';

// Validasi ID produk dari URL
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id_produk = (int)$_GET['id'];

// Ambil data produk
$data = $conn->query("SELECT * FROM produk WHERE id = $id_produk");
$produk = $data->fetch_assoc();

if (!$produk) {
    echo "Produk tidak ditemukan.";
    exit;
}

// Ambil ulasan produk
$ulasan = $conn->query("
    SELECT u.*, m.nama 
    FROM ulasan u 
    JOIN member m ON m.id = u.id_pelanggan 
    WHERE u.id_produk = $id_produk 
    ORDER BY u.tanggal DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a href="member/index.php" class="btn btn-secondary mb-4">← Kembali</a>
            <div class="card">
                <img src="assets/img/<?= htmlspecialchars($produk['gambar']) ?>" 
                     class="img-fluid mx-auto d-block" 
                     style="max-height: 400px; object-fit: contain;" 
                     alt="<?= htmlspecialchars($produk['nama']) ?>">

                <div class="card-body">
                    <h3 class="card-title"><?= htmlspecialchars($produk['nama']) ?></h3>
                    <p class="card-text"><?= nl2br(htmlspecialchars($produk['deskripsi'])) ?></p>
                    <h4 class="text-danger">Rp <?= number_format($produk['harga'], 0, ',', '.') ?></h4>

                    <div class="mt-4">
                        <?php if (isset($_SESSION['member'])): ?>
                            <a href="beli.php?id=<?= $produk['id'] ?>" class="btn btn-success btn-lg">🛒 Beli Sekarang</a>
                        <?php else: ?>
                            <a href="member/login.php" class="btn btn-warning btn-lg">🔐 Login untuk Beli</a>
                        <?php endif; ?>
                            <a href="member/ulasan.php?id=<?= $produk['id'] ?>"class="btn btn-success btn-lg">📝 Beri Ulasan</a>
                    </div>
                </div>
            </div>

            <!-- Bagian Ulasan -->
            <div class="mt-5">
                <h4>💬 Ulasan Pembeli</h4>
                <?php if ($ulasan->num_rows > 0): ?>
                    <?php while ($u = $ulasan->fetch_assoc()): ?>
                        <div class="border p-3 rounded mb-3 bg-white shadow-sm">
                            <strong><?= htmlspecialchars($u['nama']) ?></strong> -
                            <?= str_repeat("⭐", $u['rating']) ?><br>
                            <?= nl2br(htmlspecialchars($u['komentar'])) ?><br>
                            <small class="text-muted"><?= $u['tanggal'] ?></small>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-muted">Belum ada ulasan untuk produk ini.</p>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

</body>
</html>
