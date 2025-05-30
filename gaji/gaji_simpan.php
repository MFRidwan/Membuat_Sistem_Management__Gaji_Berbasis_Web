<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $karyawan_id = mysqli_real_escape_string($conn, $_POST['karyawan_id']);
    $bulan = mysqli_real_escape_string($conn, $_POST['bulan']);
    $total_gaji = mysqli_real_escape_string($conn, $_POST['total_gaji']);

    if ($karyawan_id != '' && $bulan != '' && is_numeric($total_gaji)) {
        $query = "INSERT INTO gaji (karyawan_id, bulan, total_gaji) VALUES ('$karyawan_id', '$bulan', '$total_gaji')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            echo 'success';
        } else {
            http_response_code(500);
            echo 'Gagal menyimpan ke database';
        }
    } else {
        http_response_code(400);
        echo 'Data tidak valid';
    }
}
?>
