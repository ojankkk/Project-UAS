<?php
session_start();
include '../config.php';

// Cek login
if (!isset($_SESSION['member'])) {
    header("Location: login.php");
    exit;
}

$member_id = $_SESSION['member']['id'];

// Ambil data pesanan
$data = $conn->query("
    SELECT psn.id, psn.alamat, psn.total, psn.tanggal_pesan,
           dp.jumlah, dp.harga_saat_pesan, pr.nama AS nama_produk,
           inv.nomor_invoice, inv.id AS invoice_id
    FROM pesanan psn
    JOIN detail_pesanan dp ON psn.id = dp.id_pesanan
    JOIN produk pr ON dp.id_produk = pr.id
    LEFT JOIN invoice inv ON inv.id_pesanan = psn.id
    WHERE psn.id_pelanggan = $member_id
    ORDER BY psn.tanggal_pesan DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pembelian</title>
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

<!-- Sidebar -->
<div class="sidebar">
    <h4 class="text-center">Member Area</h4>
    <div class="px-3 mb-2">👋 Hai, <?= htmlspecialchars($_SESSION['member']['nama']) ?></div>
    <a href="index.php">🛍️ Lihat Produk</a>
    <a href="riwayat.php">📜 Riwayat Pembelian</a>
    <a href="profile.php">👤 Profil</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<!-- Konten -->
<div class="main-content">
    <h3 class="mb-4">📜 Riwayat Pembelian Anda</h3>

    <a href="index.php" class="btn btn-outline-secondary mb-3">← Kembali ke Produk</a>

    <?php if ($data->num_rows > 0): ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Alamat</th>
                    <th>Tanggal</th>
                    <th>Invoice</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($row = $data->fetch_assoc()): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                    <td><?= $row['jumlah'] ?></td>
                    <td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                    <td><?= !empty($row['alamat']) ? htmlspecialchars($row['alamat']) : '' ?></td>
                    <td><?= date('d M Y H:i', strtotime($row['tanggal_pesan'])) ?></td>
                    <td>
    <?php if (!empty($row['invoice_id'])): ?>
        <a href="invoice.php?id=<?= $row['invoice_id'] ?>" class="btn btn-sm btn-primary">Cetak Invoice</a>
    <?php else: ?>
        <span class="text-muted">Belum dibuat</span>
    <?php endif; ?>
</td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">Belum ada pesanan.</div>
    <?php endif; ?>
</div>

</body>
</html>
