<?php
include '../koneksi.php';
include '../includes/sidebar.php';

$id = intval($_GET['id']);

// Ambil data gaji & karyawan
$query_gaji = "
    SELECT g.*, g.karyawan_id, k.nama, j.nama_jabatan, j.gaji_pokok, g.bulan
    FROM gaji g
    JOIN karyawan k ON g.karyawan_id = k.id
    JOIN jabatan j ON k.jabatan_id = j.id
    WHERE g.id = $id
";
$result_gaji = mysqli_query($conn, $query_gaji);
$data = mysqli_fetch_assoc($result_gaji);

if (!$data) {
    die("Data gaji tidak ditemukan.");
}

// Normalisasi nama bulan
$bulan = strtolower(trim($data['bulan']));
$karyawan_id = intval($data['karyawan_id']);

// Ambil rating
$query_rating = "
    SELECT nilai_rating 
    FROM rating 
    WHERE karyawan_id = $karyawan_id 
    AND LOWER(TRIM(bulan)) = '$bulan'
";
$result_rating = mysqli_query($conn, $query_rating);
$rating = mysqli_fetch_assoc($result_rating);

// Ambil lembur
$query_lembur = "
    SELECT 
        SUM(jumlah_jam) AS total_jam,
        SUM(jumlah_jam * tarif_per_jam) AS total_lembur,
        MAX(tarif_per_jam) AS tarif_per_jam
    FROM lembur 
    WHERE karyawan_id = $karyawan_id 
    AND LOWER(TRIM(bulan)) = '$bulan'
";
$result_lembur = mysqli_query($conn, $query_lembur);
$lembur = mysqli_fetch_assoc($result_lembur);

// Validasi NULL agar tidak tampil 0 jika tidak ada data
$jumlah_jam = $lembur['total_jam'] !== null ? $lembur['total_jam'] : 0;
$tarif_per_jam = $lembur['tarif_per_jam'] !== null ? $lembur['tarif_per_jam'] : 0;
$total_lembur = $lembur['total_lembur'] !== null ? $lembur['total_lembur'] : 0;
$total_gaji = $data['gaji_pokok'] + $total_lembur;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Gaji</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .table th {
            width: 30%;
            background-color: #f1f1f1;
        }
        .table-success td {
            font-weight: bold;
            font-size: 1.1em;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>

        <div class="col-md-10 ms-sm-auto col-lg-10 px-4 mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Detail Gaji Karyawan</h4>
                <a href="../gaji/gaji.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Nama Karyawan</th>
                                <td><?= htmlspecialchars($data['nama']) ?></td>
                            </tr>
                            <tr>
                                <th>Jabatan</th>
                                <td><?= htmlspecialchars($data['nama_jabatan']) ?></td>
                            </tr>
                            <tr>
                                <th>Bulan</th>
                                <td><?= htmlspecialchars($data['bulan']) ?></td>
                            </tr>
                            <tr>
                                <th>Gaji Pokok</th>
                                <td>Rp <?= number_format($data['gaji_pokok'], 0, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <th>Jumlah Jam Lembur</th>
                                <td><?= number_format($jumlah_jam, 0) ?> jam</td>
                            </tr>
                            <tr>
                                <th>Tarif Lembur / Jam</th>
                                <td>Rp <?= number_format($tarif_per_jam, 0, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <th>Total Lembur</th>
                                <td>Rp <?= number_format($total_lembur, 0, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <th>Rating</th>
                                <td>
                                    <?php 
                                    if(isset($rating['nilai_rating'])) {
                                        echo str_repeat('⭐', (int)$rating['nilai_rating']) . " ({$rating['nilai_rating']})";
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr class="table-success">
                                <th>Total Gaji</th>
                                <td><strong>Rp <?= number_format($total_gaji, 0, ',', '.') ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
