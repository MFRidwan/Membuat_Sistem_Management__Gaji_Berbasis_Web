<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitasi input
    $karyawan_id = (int)$_POST['karyawan_id'];
    $bulan = mysqli_real_escape_string($conn, $_POST['bulan']);
    $total_gaji = (int)$_POST['total_gaji'];
    $jumlah_jam = (float)$_POST['jumlah_jam'];
    $tarif_lembur = (int)$_POST['tarif_lembur'];
    $jumlah_jam_lembur = (float)$_POST['jumlah_jam_lembur'];
    $rating = (int)$_POST['rating'];
    $bonus = (int)$_POST['bonus'];

    // Validasi dasar
    if ($karyawan_id <= 0 || $total_gaji <= 0) {
        http_response_code(400);
        die('Data tidak valid');
    }

    // Cek duplikat (apakah sudah ada gaji untuk karyawan di bulan yang sama)
    $cek = mysqli_query($conn, "SELECT id FROM gaji WHERE karyawan_id = $karyawan_id AND bulan = '$bulan'");
    if (mysqli_num_rows($cek) > 0) {
        http_response_code(409);
        die('Data gaji untuk karyawan ini di bulan tersebut sudah ada');
    }

    // Simpan ke database (sesuaikan dengan struktur tabel Anda)
    $query = "INSERT INTO gaji (
                karyawan_id, 
                bulan, 
                total_gaji,
                jumlah_jam,
                tarif_lembur,
                jumlah_jam_lembur,
                rating,
                bonus
              ) VALUES (
                $karyawan_id, 
                '$bulan', 
                $total_gaji,
                $jumlah_jam,
                $tarif_lembur,
                $jumlah_jam_lembur,
                $rating,
                $bonus
              )";

    if (mysqli_query($conn, $query)) {
        echo 'success';
    } else {
        http_response_code(500);
        echo 'Gagal menyimpan: ' . mysqli_error($conn);
    }
} else {
    http_response_code(405);
    echo 'Method tidak diizinkan';
}