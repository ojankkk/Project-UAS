<?php
session_start();
include '../config.php';

// Cek login member
if (!isset($_SESSION['member'])) {
    header("Location: login.php");
    exit;
}

$member = $_SESSION['member'];
$id = $member['id'];

// Ambil data terbaru member
$data = $conn->query("SELECT * FROM member WHERE id = $id");
$user = $data->fetch_assoc();

// Ambil log login
$logins = $conn->query("SELECT * FROM log_login WHERE id_pelanggan = $id ORDER BY waktu_login DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Saya</title>
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
        @media print {
            body * {
                visibility: hidden;
            }

            .print-area, .print-area * {
                visibility: visible;
            }

            .print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .btn, .sidebar {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar Member -->
<div class="sidebar">
    <h4 class="text-center">Member Area</h4>
    <div class="px-3 mb-2">👋 Hai, <?= htmlspecialchars($member['nama']) ?></div>
    <a href="index.php">🛍️ Lihat Produk</a>
    <a href="riwayat.php">📜 Riwayat Pembelian</a>
    <a href="profile.php">👤 Profil</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<!-- Konten Profil -->
<div class="main-content">
    <h3 class="mb-4">👤 Profil Saya</h3>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <p><strong>Nama:</strong> <?= htmlspecialchars($user['nama']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>

            <div class="d-flex gap-2 mt-3">
                <a href="edit.php" class="btn btn-primary">✏️ Edit Profil</a>
                <a href="ubah-password.php" class="btn btn-outline-warning">🔑 Ubah Password</a>
            </div>
        </div>
    </div>

    <!-- Aktivitas Login -->
    <div class="card shadow-sm print-area">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">🕓 Riwayat Login</h5>
                <button onclick="window.print()" class="btn btn-sm btn-outline-secondary">🖨️ Cetak</button>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>IP Address</th>
                            <th>Waktu Login</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($logins->num_rows > 0): ?>
                            <?php $no = 1; while ($log = $logins->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($log['ip_address']) ?></td>
                                    <td><?= date('d-m-Y H:i:s', strtotime($log['waktu_login'])) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center">Belum ada aktivitas login.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>
