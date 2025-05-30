<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Manajemen Gaji</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Animasi Marquee Modern */
        .marquee {
            white-space: nowrap;
            display: inline-block;
            animation: marquee 15s linear infinite;
            position: relative;
        }

        @keyframes marquee {
            0%   { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }

        .karyawan-card {
            width: 180px;
            margin: 10px;
        }

        .foto-karyawan {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        body {
            background-color: #f8f9fa;
        }

        .header-bar {
            background-color: #0d6efd;
            color: white;
            padding: 10px 20px;
            overflow: hidden;
            position: relative;
        }

        .header-bar .marquee span {
            font-weight: bold;
        }

        .teks {
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Header Modern dengan Teks Berjalan -->
    <div class="header-bar">
        <div class="marquee">
            <span>👋 Selamat datang di Sistem Manajemen Gaji Karyawan PT. Sinergi Digital Media – Silakan gunakan menu di sebelah kiri untuk navigasi.</span>
        </div>
    </div>
</body>
</html>
