
<?php
include '../koneksi.php';

$id = $_GET['id'] ?? 0;
if ($id == 0) {
    header('Location: gaji.php');
    exit;
}

// Get salary data
$query = "SELECT g.*, k.nama, k.foto, j.nama_jabatan, j.gaji_pokok 
          FROM gaji g 
          JOIN karyawan k ON g.karyawan_id = k.id 
          LEFT JOIN jabatan j ON k.jabatan_id = j.id 
          WHERE g.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$gaji = $result->fetch_assoc();

if (!$gaji) {
    header('Location: gaji.php');
    exit;
}

// Get overtime data if exists
$lembur = [];
$query_lembur = "SELECT * FROM lembur WHERE karyawan_id = ? AND bulan = ?";
$stmt_lembur = $conn->prepare($query_lembur);
$stmt_lembur->bind_param("is", $gaji['karyawan_id'], $gaji['bulan']);
$stmt_lembur->execute();
$result_lembur = $stmt_lembur->get_result();
if ($result_lembur->num_rows > 0) {
    $lembur = $result_lembur->fetch_assoc();
}

// Get rating data if exists
$rating = [];
$query_rating = "SELECT * FROM rating WHERE karyawan_id = ? AND bulan = ?";
$stmt_rating = $conn->prepare($query_rating);
$stmt_rating->bind_param("is", $gaji['karyawan_id'], $gaji['bulan']);
$stmt_rating->execute();
$result_rating = $stmt_rating->get_result();
if ($result_rating->num_rows > 0) {
    $rating = $result_rating->fetch_assoc();
}

// Calculate components
$gaji_pokok = $gaji['gaji_pokok'] ?? 0;
$tunjangan_jabatan = $gaji_pokok * 0.2; // 20% of basic salary
$bonus = ($rating['nilai_rating'] ?? 0) >= 4 ? 500000 : 0;
$uang_lembur = ($lembur['tarif_per_jam'] ?? 0) * ($lembur['jumlah_jam'] ?? 0);

// Calculate total salary correctly by summing all components
$total_gaji = $gaji_pokok + $tunjangan_jabatan + $bonus + $uang_lembur;

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Gaji - <?= htmlspecialchars($gaji['nama']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid white;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .salary-breakdown {
            background-color: #f8f9fa;
            border-radius: 0.5rem;
            padding: 1.5rem;
        }
        .badge-rating {
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }
        .total-row {
            background-color: #e7f5ff;
            font-weight: bold;
        }
        .component-row:hover {
            background-color: #f1f3f5;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include '../includes/sidebar.php'; ?>
        <div class="p-4 w-100">
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">
                        <i class="bi bi-file-earmark-text text-primary me-2"></i>Detail Gaji
                    </h4>
                    <a href="gaji.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4 text-center">
                        <img src="../uploads/<?= htmlspecialchars($gaji['foto']) ?>" alt="Foto Profil" class="profile-img mb-3">
                        <h4><?= htmlspecialchars($gaji['nama']) ?></h4>
                        <h5 class="text-muted"><?= htmlspecialchars($gaji['nama_jabatan'] ?? '-') ?></h5>
                    </div>
                    <div class="col-md-8">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Informasi Gaji</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Bulan:</strong> <?= htmlspecialchars($gaji['bulan']) ?></p>
                                        <p><strong>Gaji Pokok:</strong> Rp <?= number_format($gaji_pokok, 0, ',', '.') ?></p>
                                        <p><strong>Tunjangan Jabatan:</strong> Rp <?= number_format($tunjangan_jabatan, 0, ',', '.') ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <?php if ($lembur): ?>
                                            <p><strong>Lembur:</strong> <?= $lembur['jumlah_jam'] ?> jam (Rp <?= number_format($uang_lembur, 0, ',', '.') ?>)</p>
                                        <?php endif; ?>
                                        <?php if ($rating): ?>
                                            <p><strong>Rating:</strong> 
                                                <span class="badge bg-<?= $rating['nilai_rating'] >= 4 ? 'success' : ($rating['nilai_rating'] >= 2 ? 'warning' : 'danger') ?> badge-rating">
                                                    <?= $rating['nilai_rating'] ?>
                                                </span>
                                            </p>
                                        <?php endif; ?>
                                        <p><strong>Bonus:</strong> Rp <?= number_format($bonus, 0, ',', '.') ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="salary-breakdown mb-4">
                    <h5 class="mb-3"><i class="bi bi-calculator me-2"></i>Rincian Gaji</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="60%">Komponen</th>
                                    <th class="text-end">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="component-row">
                                    <td>Gaji Pokok</td>
                                    <td class="text-end">Rp <?= number_format($gaji_pokok, 0, ',', '.') ?></td>
                                </tr>
                                <tr class="component-row">
                                    <td>Tunjangan Jabatan (20%)</td>
                                    <td class="text-end">Rp <?= number_format($tunjangan_jabatan, 0, ',', '.') ?></td>
                                </tr>
                                <?php if ($lembur): ?>
                                <tr class="component-row">
                                    <td>Uang Lembur (<?= $lembur['jumlah_jam'] ?> jam)</td>
                                    <td class="text-end">Rp <?= number_format($uang_lembur, 0, ',', '.') ?></td>
                                </tr>
                                <?php endif; ?>
                                <tr class="component-row">
                                    <td>Bonus <?= isset($rating['nilai_rating']) ? '(Rating ' . $rating['nilai_rating'] . ')' : '' ?></td>
                                    <td class="text-end">Rp <?= number_format($bonus, 0, ',', '.') ?></td>
                                </tr>
                                <tr class="total-row">
                                    <td>TOTAL GAJI</td>
                                    <td class="text-end">Rp <?= number_format($total_gaji, 0, ',', '.') ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary me-2" onclick="window.print()">
                        <i class="bi bi-printer me-1"></i> Cetak
                    </button>
                    <a href="javascript:void(0)" class="btn btn-warning me-2"
                       onclick="openEditModalGaji(<?= $gaji['id'] ?>, '<?= htmlspecialchars($gaji['nama'], ENT_QUOTES) ?>', '<?= htmlspecialchars($gaji['bulan'], ENT_QUOTES) ?>', <?= $total_gaji ?>)">
                        <i class="bi bi-pencil-square me-1"></i> Edit
                    </a>
                    <a href="gaji_hapus.php?id=<?= $gaji['id'] ?>" class="btn btn-danger" 
                       onclick="return confirm('Apakah Anda yakin ingin menghapus data gaji ini?')">
                        <i class="bi bi-trash me-1"></i> Hapus
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openEditModalGaji(id, nama, bulan, totalGaji) {
            window.location.href = `gaji.php?edit=${id}`;
        }
    </script>
</body>
</html>
