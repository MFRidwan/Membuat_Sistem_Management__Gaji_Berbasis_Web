<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = intval($_POST['id']);
    $nama  = mysqli_real_escape_string($conn, $_POST['nama_jabatan']);
    $gaji  = intval($_POST['gaji_pokok']);

    if ($id > 0 && $nama && $gaji > 0) {
        $query = mysqli_query($conn, "UPDATE jabatan SET nama_jabatan='$nama', gaji_pokok='$gaji' WHERE id=$id");
        echo $query ? "success" : "Gagal mengedit data.";
    } else {
        echo "Data tidak valid.";
    }
} else {
    echo "Akses tidak sah.";
}
