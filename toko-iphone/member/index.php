<?php
session_start();
include '../config.php';

// Cek apakah member sudah login
if (!isset($_SESSION['member'])) {
    header("Location: login.php");
    exit;
}

$member = $_SESSION['member'];

// Ambil filter kategori jika ada
$filter = '';
if (isset($_GET['kategori'])) {
    $kategori = $conn->real_escape_string($_GET['kategori']);
    $filter = "WHERE kategori = '" . $kategori . "'";
}

// Ambil daftar kategori unik
$kategori_result = $conn->query("SELECT DISTINCT kategori FROM produk WHERE kategori != '' AND kategori IS NOT NULL");

// Ambil produk sesuai filter
$result = $conn->query("SELECT * FROM produk $filter");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beranda Member - Toko iPhone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar {
            height: 100vh;
            position: fixed;
            background-color: #343a40;
            color: white;
            width: 220px;
            padding-top: 20px;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 12px 20px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .main-content {
            margin-left: 220px;
            padding: 30px;
        }
    </style>
</head>
<body>

<!-- Sidebar Member -->
<div class="sidebar">
    <h4 class="text-center">Member Area</h4>
    <div class="px-3 mb-2">👋 Hai, <?= htmlspecialchars($member['nama']) ?></div>
    <a href="index.php">🛍️ Produk</a>
    <a href="riwayat.php">📜 Riwayat Pembelian</a>
    <a href="profile.php">👤 Profil</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<!-- Konten Produk -->
<div class="main-content">
    <h3 class="mb-4">Daftar Produk</h3>

    <!-- Filter Kategori -->
    <div class="mb-4">
        <a href="index.php" class="btn btn-primary me-2 <?= !isset($_GET['kategori']) ? 'active' : '' ?>">Semua</a>
        <?php while ($kat = $kategori_result->fetch_assoc()): ?>
            <a href="?kategori=<?= urlencode($kat['kategori']) ?>"
               class="btn btn-outline-primary me-2 <?= (isset($_GET['kategori']) && $_GET['kategori'] === $kat['kategori']) ? 'active' : '' ?>">
                <?= htmlspecialchars($kat['kategori']) ?>
            </a>
        <?php endwhile; ?>
    </div>

    <!-- Daftar Produk -->
    <div class="row">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="../assets/img/<?= htmlspecialchars($row['gambar']) ?>"
                        class="card-img-top img-fluid"
                        style="height: 200px; object-fit: contain;"
                        alt="<?= htmlspecialchars($row['nama']) ?>">

                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($row['nama']) ?></h5>
                        <p class="mb-1">Kategori: <?= htmlspecialchars($row['kategori']) ?></p>
                        <p class="card-text text-danger">Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
                        <a href="../produk.php?id=<?= $row['id'] ?>" class="btn btn-primary">Lihat Detail</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>