<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_jabatan']);
    $gaji = mysqli_real_escape_string($conn, $_POST['gaji_pokok']);

    if ($nama != '' && is_numeric($gaji)) {
        $query = "INSERT INTO jabatan (nama_jabatan, gaji_pokok) VALUES ('$nama', '$gaji')";
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
