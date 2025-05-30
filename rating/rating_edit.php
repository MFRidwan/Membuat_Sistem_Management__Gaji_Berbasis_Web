<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitasi input
    $id = intval($_POST['id']);
    $karyawan_id = intval($_POST['karyawan_id']);
    $bulan = mysqli_real_escape_string($conn, $_POST['bulan']);
    $nilai_rating = intval($_POST['nilai_rating']);

    // Validasi data
    if ($id > 0 && $karyawan_id > 0 && $bulan && $nilai_rating >= 1 && $nilai_rating <= 5) {
        $query = mysqli_query($conn, "UPDATE rating 
                                    SET karyawan_id = '$karyawan_id',
                                        bulan = '$bulan',
                                        nilai_rating = '$nilai_rating'
                                    WHERE id = $id");
        echo $query ? "success" : "Gagal memperbarui data rating.";
    } else {
        echo "Data rating tidak valid.";
    }
} else {
    echo "Akses tidak sah.";
}
?>