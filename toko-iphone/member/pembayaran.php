<?php
session_start();
include '../config.php';

if (!isset($_SESSION['member'])) {
    header("Location: login.php");
    exit;
}

$member = $_SESSION['member'];
$user_id = $member['id'];

// Ambil daftar pesanan yang belum dibayar
$pesanan = $conn->query("SELECT * FROM pesanan WHERE user_id = $user_id AND status = 'menunggu_pembayaran'");

// Jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pesanan_id = $_POST['pesanan_id'];
    $metode = $_POST['metode'];
    $jumlah = $_POST['jumlah'];
    $tanggal = date('Y-m-d H:i:s');

    // Upload bukti transfer
    $bukti = $_FILES['bukti']['name'];
    $tmp = $_FILES['bukti']['tmp_name'];
    $path = "../assets/bukti/" . $bukti;
    move_uploaded_file($tmp, $path);

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO pembayaran (pesanan_id, metode_pembayaran, jumlah_bayar, bukti_transfer, status, tanggal_bayar)
                            VALUES (?, ?, ?, ?, 'pending', ?)");
    $stmt->bind_param("issss", $pesanan_id, $metode, $jumlah, $bukti, $tanggal);
    $stmt->execute();

    // Update status pesanan
    $conn->query("UPDATE pesanan SET status = 'diproses' WHERE id = $pesanan_id");

    echo "<div class='alert alert-success'>Pembayaran berhasil dikirim. Tunggu konfirmasi admin.</div>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Konfirmasi Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h3>Konfirmasi Pembayaran</h3>

<?php if ($pesanan->num_rows > 0): ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Pesanan</label>
            <select name="pesanan_id" class="form-select" required>
                <?php while ($row = $pesanan->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>">#<?= $row['id'] ?> - Rp <?= number_format($row['total'], 0, ',', '.') ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Metode Pembayaran</label>
            <select name="metode" class="form-select" required>
                <option value="Transfer Bank">Transfer Bank</option>
                <option value="QRIS">QRIS</option>
                <option value="COD">COD</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Jumlah Bayar</label>
            <input type="number" name="jumlah" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Upload Bukti Transfer</label>
            <input type="file" name="bukti" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Kirim Pembayaran</button>
    </form>
<?php else: ?>
    <div class="alert alert-info">Tidak ada pesanan yang perlu dibayar.</div>
<?php endif; ?>

</body>
</html>
