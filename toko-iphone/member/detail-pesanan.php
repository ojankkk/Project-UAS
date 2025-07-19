<?php
session_start();
include '../config.php';

// Cek login
if (!isset($_SESSION['member'])) {
    header("Location: login.php");
    exit;
}

// Ambil ID pesanan dari URL
$id_pesanan = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$id_member = $_SESSION['member']['id'];

// Cek apakah pesanan ini milik member yang login
$cek = $conn->prepare("SELECT * FROM pesanan WHERE id = ? AND id_pelanggan = ?");
$cek->bind_param("ii", $id_pesanan, $id_member);
$cek->execute();
$cek_result = $cek->get_result();

if ($cek_result->num_rows === 0) {
    echo "<div style='padding:20px; color:red;'>❌ Pesanan tidak ditemukan atau bukan milik Anda.</div>";
    exit;
}
// Cek apakah member sudah beri ulasan untuk produk ini
$id_pelanggan = $_SESSION['member']['id'];
$id_produk = $row['id_produk'];

$cek = $conn->query("SELECT * FROM ulasan WHERE id_pelanggan = $id_pelanggan AND id_produk = $id_produk");
$ulasan_ada = $cek->num_rows > 0;
?>
        <?php if (!$ulasan_ada): ?>
            <h5>Berikan Ulasan</h5>
            <form method="POST" action="proses_ulasan.php">
                <input type="hidden" name="id_produk" value="<?= $id_produk ?>">
                <div class="mb-2">
                    <label>Rating:</label><br>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <input type="radio" name="rating" value="<?= $i ?>" required> <?= $i ?> 
                    <?php endfor; ?>
                </div>
                <div class="mb-2">
                    <label>Komentar:</label>
                    <textarea name="komentar" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
            </form>
        <?php else: ?>
            <div class="alert alert-info mt-3">Anda sudah memberikan ulasan untuk produk ini.</div>
<?php endif; 

        // Ambil detail pesanan
        $stmt = $conn->prepare("SELECT dp.*, pr.nama 
                                FROM detail_pesanan dp 
                                JOIN produk pr ON dp.id_produk = pr.id 
                                WHERE dp.id_pesanan = ?");
        $stmt->bind_param("i", $id_pesanan);
        $stmt->execute();
        $result = $stmt->get_result();

        // Kurangi stok produk
        $conn->query("UPDATE produk SET stok = stok - $jumlah WHERE id = $id");

        // Catat log stok
        $stmt3 = $conn->prepare("INSERT INTO log_stok (id_produk, perubahan, keterangan) VALUES (?, ?, ?)");
        $ket = "Pembelian oleh pelanggan ID $id_pelanggan";
        $minus_jumlah = -$jumlah;
        $stmt3->bind_param("iis", $id, $minus_jumlah, $ket);
        $stmt3->execute();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <h3 class="mb-4">🧾 Detail Pesanan #<?= $id_pesanan ?></h3>
    <a href="riwayat.php" class="btn btn-outline-secondary mb-3">← Kembali ke Riwayat</a>

    <?php if ($result && $result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Produk</th>
                        <th>Harga Satuan</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0;
                    while ($row = $result->fetch_assoc()) : 
                        $subtotal = $row['harga_saat_pesan'] * $row['jumlah'];
                        $total += $subtotal;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td>Rp<?= number_format($row['harga_saat_pesan'], 0, ',', '.') ?></td>
                            <td><?= $row['jumlah'] ?></td>
                            <td>Rp<?= number_format($subtotal, 0, ',', '.') ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="3">Total</td>
                        <td>Rp<?= number_format($total, 0, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">Data pesanan tidak ditemukan atau kosong.</div>
    <?php endif; ?>
</div>

</body>
</html>
