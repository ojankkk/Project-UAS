<?php
include '../config.php';

$data = $conn->query("SELECT log_aktivitas.*, member.nama FROM log_aktivitas 
                      JOIN member ON log_aktivitas.id_member = member.id 
                      ORDER BY log_aktivitas.waktu DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Log Aktivitas Member</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
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
    </style>
</head>
<body class="bg-light">
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
    <div class="container">
        <h3 class="mb-4">📋 Log Aktivitas Member</h3>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Member</th>
                    <th>Aktivitas</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($log = $data->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($log['nama']); ?></td>
                        <td><?= htmlspecialchars($log['aktivitas']); ?></td>
                        <td><?= $log['waktu']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="dashboard.php" class="btn btn-secondary">⬅ Kembali</a>
    </div>
</body>
</html>
