<?php
session_start();
include '../config.php';

// Cek login
if (!isset($_SESSION['member'])) {
    header("Location: login.php");
    exit;
}

// Validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID invoice tidak valid.";
    exit;
}

$id_invoice = (int)$_GET['id'];

// Ambil data invoice + pesanan
$query = $conn->query("
    SELECT 
        psn.*, 
        inv.nomor_invoice, 
        inv.tanggal_cetak, 
        mb.nama AS nama_pelanggan
    FROM invoice inv
    JOIN pesanan psn ON inv.id_pesanan = psn.id
    JOIN member mb ON psn.id_pelanggan = mb.id
    WHERE inv.id = $id_invoice
");

if ($query->num_rows == 0) {
    echo "Invoice tidak ditemukan.";
    exit;
}

$invoice = $query->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice <?= htmlspecialchars($invoice['nomor_invoice']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 40px;
        }
        .invoice-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,.1);
            max-width: 800px;
            margin: auto;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                background-color: white;
                padding: 0;
            }
        }
    </style>
</head>
<body>

<div class="invoice-box">
    <h2 class="mb-4">🧾 Invoice</h2>

    <table class="table">
        <tr>
            <th>Nomor Invoice</th>
            <td><?= htmlspecialchars($invoice['nomor_invoice']) ?></td>
        </tr>
        <tr>
            <th>Tanggal Cetak</th>
            <td><?= date('d M Y H:i', strtotime($invoice['tanggal_cetak'])) ?></td>
        </tr>
        <tr>
            <th>Nama Pelanggan</th>
            <td><?= htmlspecialchars($invoice['nama_pelanggan']) ?></td>
        </tr>
        <tr>
            <th>Alamat</th>
            <td><?= htmlspecialchars($invoice['alamat']) ?></td>
        </tr>
        <tr>
            <th>Total Pembayaran</th>
            <td><strong>Rp <?= number_format($invoice['total'], 0, ',', '.') ?></strong></td>
        </tr>
    </table>

    <div class="mt-4 no-print">
        <a href="riwayat.php" class="btn btn-secondary">← Kembali</a>
        <button onclick="window.print()" class="btn btn-primary">🖨️ Cetak Invoice</button>
    </div>
</div>

</body>
</html>
