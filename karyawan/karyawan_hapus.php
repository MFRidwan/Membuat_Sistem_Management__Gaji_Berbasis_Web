<?php
include '../koneksi.php';

$id = intval($_GET['id']);

// Hapus semua data terkait
mysqli_query($conn, "DELETE FROM rating WHERE karyawan_id = $id");
mysqli_query($conn, "DELETE FROM gaji WHERE karyawan_id = $id");
mysqli_query($conn, "DELETE FROM karyawan WHERE id = $id");

// Cek apakah karyawan terhapus
if (mysqli_affected_rows($conn) > 0) {
    echo "success";
} else {
    echo "failed";
}
?>
