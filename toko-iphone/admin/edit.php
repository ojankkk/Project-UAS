<?php
session_start();
include '../config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: dashboard.php");
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
    $nama       = $conn->real_escape_string($_POST['nama']);
    $deskripsi  = $conn->real_escape_string($_POST['deskripsi']);
    $harga      = (int)$_POST['harga'];
    $kategori   = $conn->real_escape_string($_POST['kategori']);
    $stok_baru  = (int)$_POST['stok'];
    $stok_lama  = (int)$produk['stok'];
    $gambar_baru = $_FILES['gambar']['name'];
    $tmp         = $_FILES['gambar']['tmp_name'];

    if (!empty($gambar_baru)) {
        $tujuan = '../assets/img/' . $gambar_baru;
        move_uploaded_file($tmp, $tujuan);
        $gambar = $gambar_baru;
    } else {
        $gambar = $produk['gambar'];
    }

    // Update ke database
    $sql = "UPDATE produk SET 
                nama = '$nama',
                deskripsi = '$deskripsi',
                harga = $harga,
                kategori = '$kategori',
                gambar = '$gambar',
                stok = $stok_baru
            WHERE id = $id";

    if ($conn->query($sql)) {
        // Log stok jika ada perubahan
        $selisih = $stok_baru - $stok_lama;
        if ($selisih !== 0) {
            // Ambil nama admin dari session
            $nama_admin = isset($_SESSION['admin']['nama']) ? $_SESSION['admin']['nama'] : 'Admin';

            // Catat log stok
            $keterangan = "Edit produk oleh admin: $nama_admin (stok dari $stok_lama ke $stok_baru)";
            $stmt = $conn->prepare("INSERT INTO log_stok (id_produk, perubahan, keterangan) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $id, $selisih, $keterangan);
            $stmt->execute();
        }

        $pesan = "Produk berhasil diperbarui!";
        $produk = $conn->query("SELECT * FROM produk WHERE id = $id")->fetch_assoc();
    } else {
        $pesan = "Gagal memperbarui produk: " . $conn->error;
    }
}
?>
