<?php
include '../koneksi.php';
include '../includes/sidebar.php'; 
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ambil ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : 0;

// Query ambil data jabatan berdasarkan ID
$query = mysqli_query($conn, "SELECT * FROM jabatan WHERE id = $id");
$row = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Jabatan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .wrapper {
            margin-left: 320px;
        }
    </style>
</head>

<body>
    <div class="container my-5 wrapper"  data-aos="fade-up">
        <h2 class="text-center fw-bold mb-4 text-uppercase text-primary" data-aos="fade-down">Detail Rating</h2>


                <?php if ($row): ?>
                    <div class="card shadow-lg p-2" data-aos="zoom-in">
                        <div class="card-body">
                            <h4 class="card-title mb-3">
                                <i class="bi bi-person-badge-fill text-primary me-2"></i><?= htmlspecialchars($row['nama_jabatan']) ?>
                            </h4>
                            <p class="mb-2">
                                <strong>Deskripsi:</strong><br>
                                <?= isset($row['deskripsi']) && $row['deskripsi'] !== null ? nl2br(htmlspecialchars($row['deskripsi'])) : '<em>Tidak ada deskripsi.</em>' ?>
                            </p>
                            <p class="mb-0">
                                <span class="badge bg-success">
                                    Gaji Pokok: Rp<?= number_format($row['gaji_pokok'], 0, ',', '.') ?>
                                </span>
                            </p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning mt-3">
                        Data jabatan tidak ditemukan.
                    </div>
                <?php endif; ?>

                <a href="../jabatan/jabatan.php" class="btn btn-outline-secondary mt-4">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init();
</script>
</body>
</html>




