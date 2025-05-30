<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']); // Tambahkan ini agar variabel $id ada
    $bulan = mysqli_real_escape_string($conn, $_POST['bulan']);
    $gaji = intval($_POST['total_gaji']);

    if ($id > 0 && $bulan && $gaji > 0) {
        $query = "UPDATE gaji SET bulan = '$bulan', total_gaji = $gaji WHERE id = $id";
        if (mysqli_query($conn, $query)) {
            echo $query ? "success" : "Gagal mengedit data.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Data tidak valid.";
    }
}
?>
