<?php
// rating_simpan.php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $karyawan_id = mysqli_real_escape_string($conn, $_POST['karyawan_id']);
    $bulan = mysqli_real_escape_string($conn, $_POST['bulan']);
    $nilai_rating = mysqli_real_escape_string($conn, $_POST['nilai_rating']);

    if ($karyawan_id != '' && $bulan != '' && $nilai_rating != '') {
        $query = "INSERT INTO rating (karyawan_id, bulan, nilai_rating) VALUES ('$karyawan_id', '$bulan', '$nilai_rating')";
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
