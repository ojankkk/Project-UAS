<?php
session_start();
include 'config.php';

// Cek apakah member sudah login
if (!isset($_SESSION['member'])) {
    header("Location: member/login.php");
    exit;
}

$member = $_SESSION['member'];
$id_pelanggan = $member['id'];

// Ambil ID produk dari URL
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int)$_GET['id'];
$data = $conn->query("SELECT * FROM produk WHERE id = $id");
$produk = $data->fetch_assoc();

if (!$produk) {
    echo "Produk tidak ditemukan.";
    exit;
}

$pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alamat = $conn->real_escape_string($_POST['alamat']);
    $jumlah = (int)$_POST['jumlah'];
    $harga = $produk['harga'];
    $total = $harga * $jumlah;

    // Simpan ke pesanan
    $stmt = $conn->prepare("INSERT INTO pesanan (id_pelanggan, alamat, total, tanggal_pesan) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("isd", $id_pelanggan, $alamat, $total);
    $stmt->execute();
    $id_pesanan = $stmt->insert_id;

    // Simpan ke detail pesanan
    $stmt2 = $conn->prepare("INSERT INTO detail_pesanan (id_pesanan, id_produk, jumlah, harga_saat_pesan) VALUES (?, ?, ?, ?)");
    $stmt2->bind_param("iiid", $id_pesanan, $id, $jumlah, $harga);
    $stmt2->execute();

    // Setelah insert ke detail_pesanan dan redirect
    $aktivitas = "Melakukan pemesanan produk ID $id sebanyak $jumlah";
    $conn->query("INSERT INTO log_aktivitas (id_member, aktivitas) VALUES ($id_pelanggan, '$aktivitas')");

    // Tambahkan invoice
    $nomor_invoice = 'INV-' . date('YmdHis') . '-' . rand(1000, 9999);
    $stmt3 = $conn->prepare("INSERT INTO invoice (id_pesanan, nomor_invoice) VALUES (?, ?)");
    $stmt3->bind_param("is", $id_pesanan, $nomor_invoice);
    $stmt3->execute();


    echo "<script>alert('Pesanan berhasil!'); location='member/riwayat.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .produk-img {
            max-height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-light">
<div class="container my-5">
    <a href="member/index.php" class="btn btn-secondary mb-4">← Kembali</a>
    <div class="card shadow border-0">
        <div class="row g-0">
            <div class="col-md-5 text-center">
                <img src="assets/img/<?= htmlspecialchars($produk['gambar']) ?>" 
                    class="img-fluid mx-auto d-block" 
                    style="max-height: 400px; object-fit: contain;" 
                    alt="<?= htmlspecialchars($produk['nama']) ?>">
            </div>
            <div class="col-md-7">
                <div class="card-body">
                    <h4 class="fw-bold"><?= htmlspecialchars($produk['nama']) ?></h4>
                    <p class="text-muted"><?= htmlspecialchars($produk['deskripsi']) ?></p>
                    <h5 class="text-danger fw-bold mb-4">Rp <?= number_format($produk['harga'], 0, ',', '.') ?></h5>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($member['nama']) ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="<?= htmlspecialchars($member['email']) ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat Pengiriman</label>
                            <textarea name="alamat" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah</label>
                            <input type="number" name="jumlah" class="form-control" value="1" min="1" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            🛒 Beli Sekarang
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
