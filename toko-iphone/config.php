<?php
$host = 'localhost';
$user = 'root'; // default di Laragon/XAMPP
$pass = '123';     // kosongkan jika pakai Laragon/XAMPP
$db   = 'toko_iphone';

$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
