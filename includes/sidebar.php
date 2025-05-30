<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <title>Sidebar Tetap di Kiri</title>
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            overflow-y: auto;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }

        .logo-wrapper {
            padding: 20px 0;
        }

        .nav-link {
            transition: background-color 0.3s ease, color 0.3s ease;
            border-radius: 0.375rem;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }

        .nav-link.active {
            background-color: #0d6efd;
            color: #ffffff;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>

    <div class="d-flex">
        <!-- Sidebar -->
        <div class="d-flex flex-column bg-dark text-white shadow sidebar">
            <div class="d-flex flex-column justify-content-center align-items-center text-center logo-wrapper">
                <img src="../assets/img/logo-bulat.png" alt="Logo" width="100" height="100" class="rounded-circle mb-2">
                <span class="fs-6 fw-semibold text-uppercase px-3 text-white text-wrap">Sistem Manajemen Gaji</span>
            </div>

            <hr class="border-secondary my-2">

            <ul class="nav nav-pills flex-column mb-auto px-3">
                <li class="nav-item">
                    <a href="../dashboard.php" class="nav-link <?= $currentPage == 'dashboard.php' ? 'active' : 'text-white' ?>">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="../karyawan/karyawan.php" class="nav-link <?= $currentPage == 'karyawan.php' ? 'active' : 'text-white' ?>">
                        <i class="bi bi-people-fill me-2"></i> Daftar Karyawan
                    </a>
                </li>
                <li>
                    <a href="../jabatan/jabatan.php" class="nav-link <?= $currentPage == 'jabatan.php' ? 'active' : 'text-white' ?>">
                        <i class="bi bi-briefcase-fill me-2"></i> Daftar Jabatan
                    </a>
                </li>
                <li>
                    <a href="../rating/rating.php" class="nav-link <?= $currentPage == 'rating.php' ? 'active' : 'text-white' ?>">
                        <i class="bi bi-star-fill me-2"></i> Daftar Rating
                    </a>
                </li>
                <li>
                    <a href="../lembur/lembur.php" class="nav-link <?= $currentPage == 'lembur.php' ? 'active' : 'text-white' ?>">
                        <i class="bi bi-clock-fill me-2"></i> Tarif Lembur
                    </a>
                </li>
                <li>
                    <a href="../gaji/gaji.php" class="nav-link <?= $currentPage == 'gaji.php' ? 'active' : 'text-white' ?>">
                        <i class="bi bi-cash-stack me-2"></i> Gaji Karyawan
                    </a>
                </li>
            </ul>

            <hr class="border-secondary my-2">
            <div class="text-center text-muted small pb-3">&copy; 2025</div>
        </div>

        <!-- Konten -->
        <div class="content">
            <!-- Konten halaman kamu -->
        </div>
    </div>
</body>

</html>
