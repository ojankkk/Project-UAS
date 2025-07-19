<?php
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama      = $_POST['nama'];
    $harga     = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];
    $kategori  = $_POST['kategori'];
    $tambah    = (int)$_POST['jumlah']; // Tambah stok awal

    // Upload gambar
    $gambar = $_FILES['gambar']['name'];
    $tmp    = $_FILES['gambar']['tmp_name'];
    $target = "../assets/img/" . $gambar;

    if (move_uploaded_file($tmp, $target)) {
        // Insert produk baru
        $stmt = $conn->prepare("INSERT INTO produk (nama, harga, deskripsi, kategori, gambar, stok) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sdsssi", $nama, $harga, $deskripsi, $kategori, $gambar, $tambah);
        $stmt->execute();

        // Dapatkan ID produk baru
        $id_produk = $stmt->insert_id;

        // Catat ke log stok
        $ket = "Stok awal saat tambah produk";
        $stmt2 = $conn->prepare("INSERT INTO log_stok (id_produk, perubahan, keterangan, tanggal) VALUES (?, ?, ?, NOW())");
        $stmt2->bind_param("iis", $id_produk, $tambah, $ket);
        $stmt2->execute();

        header("Location: dashboard.php");
        exit;
    } else {
        echo "❌ Gagal upload gambar.";
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar {
            height: 100vh;
            position: fixed;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
            width: 220px;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 10px 20px;
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
<div class="container py-5">
    <div class="row">
    <h2 class="mb-4">Tambah Produk</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Nama Produk</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label>Kategori</label>
            <select name="kategori" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="iPhone">iPhone</option>
                <option value="Samsung">Samsung</option>
                <option value="Oppo">Oppo</option>
                <option value="Xiaomi">Xiaomi</option>
                <option value="Vivo">Vivo</option>
                <!-- Tambahkan sesuai kebutuhan -->
            </select>
        </div>
        <div class="mb-3">
            <label>Gambar</label>
            <input type="file" name="gambar" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Simpan Produk</button>
        <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
    </form>
    </div>
</div>

</body>
</html>
