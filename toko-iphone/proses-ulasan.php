<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pelanggan = $_SESSION['member']['id'];
    $id_produk    = $_POST['id_produk'];
    $rating       = (int)$_POST['rating'];
    $komentar     = mysqli_real_escape_string($conn, $_POST['komentar']);

    $stmt = $conn->prepare("INSERT INTO ulasan (id_produk, id_pelanggan, rating, komentar, tanggal) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("iiis", $id_produk, $id_pelanggan, $rating, $komentar);
    
    if ($stmt->execute()) {
        header("Location: detail_pesanan.php?status=ulasan_tersimpan");
    } else {
        echo "Gagal menyimpan ulasan.";
    }
}
?>
