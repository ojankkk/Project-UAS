<?php
session_start();
include '../config.php';

// Cek apakah admin login
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Ambil data ulasan
$sql = "SELECT u.*, p.nama AS nama_produk, m.nama AS id_pelanggan
        FROM ulasan u
        JOIN produk p ON u.id_produk = p.id
        JOIN member m ON u.id_pelanggan = m.id
        ORDER BY u.tanggal DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ulasan Produk - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
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
        .content {
            margin-left: 240px;
            padding: 20px;
        }
    </style>
</head>
<body>
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

<div class="content">
    <h3>Daftar Ulasan Produk</h3>
    <table class="table table-bordered table-striped mt-4">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Pelanggan</th>
                <th>Rating</th>
                <th>Komentar</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): $no = 1; ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                        <td><?= htmlspecialchars($row['id_pelanggan']) ?></td>
                        <td><?= str_repeat("⭐", $row['rating']) ?></td>
                        <td><?= nl2br(htmlspecialchars($row['komentar'])) ?></td>
                        <td><?= $row['tanggal'] ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">Belum ada ulasan.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
