<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $jabatan_id = intval($_POST['jabatan_id']);
    $tarif_per_jam = intval($_POST['tarif_per_jam']);
    $jumlah_jam = intval($_POST['jumlah_jam']);

    if ($id > 0 && $jabatan_id > 0 && $tarif_per_jam > 0 && $jumlah_jam > 0) {
        $query = mysqli_query($conn, "UPDATE lembur SET 
            jabatan_id = $jabatan_id, 
            tarif_per_jam = $tarif_per_jam, 
            jumlah_jam = $jumlah_jam 
            WHERE id = $id");

        echo $query ? "success" : "Gagal mengedit data.";
    } else {
        echo "Data tidak valid.";
    }
} else {
    echo "Akses tidak sah.";
}
?>
