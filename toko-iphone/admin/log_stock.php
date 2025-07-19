<?php
session_start();
include '../config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Ambil semua log stok dan join dengan nama produk
$query = "
    SELECT log_stok.*, produk.nama 
    FROM log_stok 
    JOIN produk ON log_stok.id_produk = produk.id
    ORDER BY log_stok.tanggal DESC
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Log Stok Barang</title>
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
        .table th, .table td {
            vertical-align: middle;
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
    <a href="signup.php">👤 Tambah Admin</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<!-- Konten -->
<div class="main-content">
    <h3 class="mb-4">Riwayat Log Stok Barang</h3>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Perubahan</th>
                <th>Keterangan</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): 
                $no = 1;
                while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td class="<?= $row['perubahan'] < 0 ? 'text-danger' : 'text-success' ?>">
                        <?= $row['perubahan'] > 0 ? '+' : '' ?><?= $row['perubahan'] ?>
                    </td>
                    <td><?= htmlspecialchars($row['keterangan']) ?></td>
                    <td><?= date('d-m-Y H:i', strtotime($row['tanggal'])) ?></td>
                </tr>
            <?php endwhile; else: ?>
                <tr>
                    <td colspan="5" class="text-center">Belum ada log stok.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
