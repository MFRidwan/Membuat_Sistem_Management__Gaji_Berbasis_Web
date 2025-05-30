<?php
include '../koneksi.php';
include '../includes/sidebar.php';

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT lembur.*, jabatan.nama_jabatan 
    FROM lembur 
    JOIN jabatan ON lembur.jabatan_id = jabatan.id 
    WHERE lembur.id = $id
"));

$tarif = $data['tarif_per_jam'];
$jam = $data['jumlah_jam'];
$total = $tarif * $jam;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Tarif Lembur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 1rem;
        }
        .wrapper {
            margin-left: 320px;
        }
    </style>
</head>
<body>

        <div class="container my-5 wrapper"  data-aos="fade-up">
                <h2 class="text-center fw-bold mb-4 text-uppercase text-primary" data-aos="fade-down">Detail Rating</h2>

                            <div class="card shadow-lg border-0 rounded">
                                <div class="card-body">
                                <h5 class="mb-3">
                                    <i class="bi bi-person-badge-fill text-primary me-2"></i>Jabatan:
                                    <strong><?= $data['nama_jabatan']; ?></strong>
                                </h5>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <strong>Tarif per Jam:</strong> Rp <?= number_format($tarif, 0, ',', '.'); ?>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Jumlah Jam:</strong> <?= $jam; ?> jam
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Total Lembur:</strong> <span class="text-success fw-bold">Rp <?= number_format($total, 0, ',', '.'); ?></span>
                                    </li>
                                </ul>
                            </div>
                            </div>

                        <a href="../lembur/lembur.php" class="btn btn-outline-secondary mt-4">
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
