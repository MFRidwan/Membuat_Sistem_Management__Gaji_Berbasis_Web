<?php
include '../koneksi.php';
include '../includes/sidebar.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ambil ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : 0;

// Query ambil data rating dan join ke karyawan
$query = mysqli_query($conn, "SELECT rating.*, karyawan.nama FROM rating 
                              JOIN karyawan ON rating.karyawan_id = karyawan.id 
                              WHERE rating.id = $id");
$row = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Rating Karyawan</title>
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
                    <div class="card shadow-lg border-0 rounded">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="bi bi-star-fill text-warning me-2"></i><?= htmlspecialchars($row['nama']) ?>
                            </h5>
                            <p class="mb-2">
                                <strong>Bulan:</strong> <?= htmlspecialchars($row['bulan']) ?>
                            </p>
                            <p class="mb-0">
                                <strong>Nilai Rating:</strong>
                                <span class="text-primary fs-5">
                                    <?= htmlspecialchars($row['nilai_rating']) ?>
                                    <?php for ($i = 0; $i < $row['nilai_rating']; $i++): ?>
                                        <i class="bi bi-star-fill text-warning"></i>
                                    <?php endfor; ?>
                                </span>
                            </p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning mt-3">
                        Data rating tidak ditemukan.
                    </div>
                <?php endif; ?>

                <a href="../rating/rating.php" class="btn btn-outline-secondary mt-4">
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