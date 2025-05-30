<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama           = mysqli_real_escape_string($conn, $_POST['nama']);
    $umur           = mysqli_real_escape_string($conn, $_POST['umur']);
    $jenis_kelamin  = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
    $jabatan_id     = mysqli_real_escape_string($conn, $_POST['jabatan_id']);
    $alamat         = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_hp          = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $status         = mysqli_real_escape_string($conn, $_POST['status']);
    $tanggal        = mysqli_real_escape_string($conn, $_POST['tanggal_bergabung']);
    $rating         = mysqli_real_escape_string($conn, $_POST['nilai_rating']);

    // Upload foto
    $foto_name = $_FILES['foto']['name'];
    $foto_tmp  = $_FILES['foto']['tmp_name'];
    $upload_dir = '../uploads/';
    $foto_path = '';

    if (!empty($foto_name)) {
        $foto_path = uniqid() . '_' . basename($foto_name); // unique filename
        if (!move_uploaded_file($foto_tmp, $upload_dir . $foto_path)) {
            http_response_code(500);
            echo 'Gagal upload foto';
            exit;
        }
    }

    // Simpan ke tabel karyawan
    $query = "INSERT INTO karyawan (nama, umur, jenis_kelamin, jabatan_id, alamat, no_hp, status, foto, tanggal_bergabung)
              VALUES ('$nama', '$umur', '$jenis_kelamin', '$jabatan_id', '$alamat', '$no_hp', '$status', '$foto_path', '$tanggal')";
    
    $result = mysqli_query($conn, $query);

    if ($result) {
        $karyawan_id = mysqli_insert_id($conn);
        $bulan = date('Y-m');

        $rating_query = "INSERT INTO rating (karyawan_id, bulan, nilai_rating)
                         VALUES ('$karyawan_id', '$bulan', '$rating')";
        $rating_result = mysqli_query($conn, $rating_query);

        if ($rating_result) {
            echo 'success';
        } else {
            http_response_code(500);
            echo 'Gagal menyimpan rating';
        }
    } else {
        http_response_code(500);
        echo 'Gagal menyimpan karyawan';
    }
}
?>
