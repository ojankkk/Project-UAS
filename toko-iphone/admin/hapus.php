<?php
session_start();
include '../config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Cek apakah ada ID
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id = (int)$_GET['id'];

// Ambil dulu nama file gambarnya untuk dihapus dari folder
$data = $conn->query("SELECT gambar FROM produk WHERE id = $id");
if ($data->num_rows > 0) {
    $row = $data->fetch_assoc();
    $gambar = $row['gambar'];

    // Hapus file gambar jika ada
    if (file_exists("../assets/img/" . $gambar)) {
        unlink("../assets/img/" . $gambar);
    }

    // Hapus data dari database
    $conn->query("DELETE FROM produk WHERE id = $id");
}

header("Location: dashboard.php");
exit;
