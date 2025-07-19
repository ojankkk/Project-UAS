<?php
session_start();
include '../config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$result = $conn->query("SELECT * FROM produk");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar {
            height: 100vh;
            position: fixed;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
            width: 220px;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 10px 20px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .main-content {
            margin-left: 220px;
            padding: 30px;
        }
        .table img {
            width: 80px;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4 class="text-center">Admin Panel</h4>
    <a href="dashboard.php">📦 Data Produk</a>
    <a href="tambah.php">➕ Tambah Produk</a>
    <a href="log_stock.php">📊 Log Stok</a>
    <a href="log_aktivitas.php">🧾 Log Aktivitas Member</a>
    <a href="ulasan.php">📝 Lihat Ulasan</a>
    <a href="signup.php">👤 Tambah Admin</a>
    <a href="logout.php">🚪 Logout</a>


</div>

<!-- Konten Utama -->
<div class="main-content">
    <h3 class="mb-4">Daftar Produk iPhone</h3>

    <table class="table table-bordered table-hover bg-white">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                <td><img src="../assets/img/<?= $row['gambar'] ?>" alt="gambar"></td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
