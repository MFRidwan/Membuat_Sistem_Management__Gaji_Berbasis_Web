<?php 
include 'koneksi.php';
include 'includes/header.php'; 
?>
<?php
// Statistik data
$total_karyawan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM karyawan"))['total'];
$total_jabatan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM jabatan"))['total'];
$rata_rating = mysqli_fetch_assoc(mysqli_query($conn, "SELECT AVG(nilai_rating) AS rata FROM rating"))['rata'];
$jumlah_manager = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM karyawan k JOIN jabatan j ON k.jabatan_id = j.id WHERE j.nama_jabatan = 'Manager'"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Sistem Manajemen Gaji</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .kartu-karyawan {
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .kartu-karyawan:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .foto-karyawan {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .judul-dashboard {
            font-size: 2.2rem;
            font-weight: 700;
            background: linear-gradient(to right, #0d6efd, #6610f2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .subjudul-dashboard {
            font-size: 1.1rem;
            color: #6c757d;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
        }

        .text-warning {
            font-size: 16px;
        }

        .badge-role {
            font-size: 13px;
        }

        .btn-detail {
            font-size: 14px;
            padding: 4px 12px;
        }

        hr.custom-hr {
            border-top: 3px solid #0d6efd;
            opacity: 0.75;
            width: 80px;
            margin: 0 auto 30px;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <?php include 'includes/sidebar.php'; ?>
    <div class="p-4 w-100">
        <div class="text-center mb-4">
            <h1 class="judul-dashboard mb-2">
                <i class="bi bi-speedometer2 me-2"></i>Selamat Datang di <span class="fw-bold">PT Sinergi Digital Media</span>
            </h1>
            <p class="subjudul-dashboard">Lihat informasi karyawan terbaru dengan mudah dan cepat</p>
            <hr class="custom-hr">
        </div>

        <!-- Statistik Ringkas -->
        <div class="row text-center mb-5">
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <i class="bi bi-people-fill text-primary fs-2 mb-2"></i>
                        <h6 class="mb-1">Total Karyawan</h6>
                        <h4><?= $total_karyawan ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <i class="bi bi-briefcase-fill text-success fs-2 mb-2"></i>
                        <h6 class="mb-1">Total Jabatan</h6>
                        <h4><?= $total_jabatan ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <i class="bi bi-star-fill text-warning fs-2 mb-2"></i>
                        <h6 class="mb-1">Rata-rata Rating</h6>
                        <h4><?= number_format($rata_rating ?? 0, 1) ?> ⭐</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <i class="bi bi-person-badge-fill text-info fs-2 mb-2"></i>
                        <h6 class="mb-1">Jumlah Manager</h6>
                        <h4><?= $jumlah_manager ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu Karyawan -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php
            $query = mysqli_query($conn, "SELECT karyawan.*, jabatan.nama_jabatan 
                                          FROM karyawan 
                                          JOIN jabatan ON karyawan.jabatan_id = jabatan.id 
                                          ORDER BY karyawan.id DESC LIMIT 100");

            $bulan_ini = date('Y-m');

            while ($row = mysqli_fetch_assoc($query)) {
                $id_karyawan = $row['id'];
                $rating_q = mysqli_query($conn, "SELECT nilai_rating FROM rating WHERE karyawan_id = $id_karyawan AND bulan = '$bulan_ini'");
                $data_rating = mysqli_fetch_assoc($rating_q);
                $nilai_rating = $data_rating['nilai_rating'] ?? '-';
                $bintang = is_numeric($nilai_rating) ? str_repeat('⭐', $nilai_rating) : '-';

                $jabatan = strtolower($row['nama_jabatan']);
                $badge_class = 'secondary';
                if ($jabatan === 'manager') {
                    $badge_class = 'primary';
                } elseif ($jabatan === 'staff') {
                    $badge_class = 'success';
                } elseif ($jabatan === 'admin') {
                    $badge_class = 'danger';
                } elseif ($jabatan === 'supervisor') {
                    $badge_class = 'dark';
                }

                echo '
                <div class="col">
                    <div class="card kartu-karyawan shadow-sm h-100">
                        <img src="uploads/' . $row['foto'] . '" class="foto-karyawan card-img-top" alt="Foto Karyawan">
                        <div class="card-body text-center">
                            <h5 class="card-title mb-1">' . $row['nama'] . '</h5>
                            <div class="text-warning mb-1">Rating: ' . $bintang . '</div>
                            <span class="badge bg-' . $badge_class . ' badge-role mb-2">' . $row['nama_jabatan'] . '</span><br>
                            <a href="../dashboard_detail.php?id=' . $id_karyawan . '" class="btn btn-outline-primary btn-sm btn-detail">
                                <i class="bi bi-eye"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>';
            }
            ?>
        </div>
    </div>
</div>


<?php include 'includes/footer.php'; ?>
</body>
</html>
