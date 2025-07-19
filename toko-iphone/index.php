<?php
include 'config.php';
session_start();

// Ambil semua kategori unik
$kategoriQuery = $conn->query("SELECT DISTINCT kategori FROM produk");

// Ambil kategori aktif dari URL jika ada
$filter_kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';

// Query produk berdasarkan kategori (jika ada filter)
if ($filter_kategori) {
    $stmt = $conn->prepare("SELECT * FROM produk WHERE kategori = ?");
    $stmt->bind_param("s", $filter_kategori);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM produk");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Toko iPhone</title>
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

<div class="sidebar">
    <h4 class="text-center">Ojankk Phone</h4>
    <a href="index.php">🏠 Beranda</a>

    <?php if (isset($_SESSION['member'])): ?>
        <div class="text-white px-3">👋 Hai, <?= $_SESSION['member']['nama'] ?></div>
        <a href="member/logout.php">🚪 Logout</a>
    <?php else: ?>
        <a href="member/login.php">🔐 Login</a>
        <a href="member/signup.php">✍️ Daftar</a>
    <?php endif; ?>
</div>

<!-- Konten Utama -->
<div class="main-content">
    <h2 class="mb-4">Daftar Produk <?= $filter_kategori ? htmlspecialchars($filter_kategori) : '' ?></h2>

    <!-- Tab Kategori -->
    <ul class="nav nav-pills mb-4">
        <li class="nav-item">
            <a class="nav-link <?= $filter_kategori == '' ? 'active' : '' ?>" href="index.php">Semua</a>
        </li>
        <?php while($kategori = $kategoriQuery->fetch_assoc()): ?>
            <li class="nav-item">
                <a class="nav-link <?= $filter_kategori == $kategori['kategori'] ? 'active' : '' ?>" 
                   href="index.php?kategori=<?= urlencode($kategori['kategori']) ?>">
                   <?= htmlspecialchars($kategori['kategori']) ?>
                </a>
            </li>
        <?php endwhile; ?>
    </ul>

    <!-- Daftar Produk -->
    <div class="row">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="assets/img/<?= $row['gambar'] ?>" 
                        class="card-img-top img-fluid"
                        style="height: 200px; object-fit: contain;"
                        alt="<?= htmlspecialchars($row['nama']) ?>">

                    <div class="card-body">
                        <h5><?= htmlspecialchars($row['nama']) ?></h5>
                        <p>Kategori: <?= htmlspecialchars($row['kategori']) ?></p>
                        <p class="card-text text-danger">Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
                        <a href="produk.php?id=<?= $row['id'] ?>" class="btn btn-primary">Lihat Detail</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
