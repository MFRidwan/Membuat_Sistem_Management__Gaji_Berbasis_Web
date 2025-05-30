

<?php
include '../koneksi.php';

// Tangani proses penyimpanan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $jabatan_id = mysqli_real_escape_string($conn, $_POST['jabatan_id']);
    $tarif_per_jam = mysqli_real_escape_string($conn, $_POST['tarif_per_jam']);
    $jumlah_jam = mysqli_real_escape_string($conn, $_POST['jumlah_jam']);

    // Validasi data
    if ($jabatan_id && $tarif_per_jam && $jumlah_jam) {
        // Simpan data ke tabel lembur
        $query = "INSERT INTO lembur (jabatan_id, tarif_per_jam, jumlah_jam) VALUES ('$jabatan_id', '$tarif_per_jam', '$jumlah_jam')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            echo "success";
        } else {
            http_response_code(500);
            echo "Gagal menyimpan data ke database";
        }
    } else {

        http_response_code(400);
        echo "Data tidak valid";
    }
}
?>

