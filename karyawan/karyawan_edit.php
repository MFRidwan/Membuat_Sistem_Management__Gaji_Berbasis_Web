<?php
include '../koneksi.php';




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $umur = $_POST['umur'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $jabatan_id = $_POST['jabatan_id'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $status = $_POST['status'];
    $tanggal_bergabung = $_POST['tanggal_bergabung'];
    $nilai_rating = $_POST['nilai_rating'];

    // Penanganan Upload Foto
    $foto_baru = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    $upload_dir = '../uploads/';

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if (!empty($foto_baru)) {
        move_uploaded_file($tmp, $upload_dir . $foto_baru);
        $foto = $foto_baru;
    } else {
        // Ambil foto lama dari DB
        $getFoto = mysqli_query($conn, "SELECT foto FROM karyawan WHERE id = '$id'");
        $fotoData = mysqli_fetch_assoc($getFoto);
        $foto = $fotoData['foto'];
    }

    // Update data karyawan
    $update = mysqli_query($conn, "UPDATE karyawan SET 
        nama = '$nama',
        umur = '$umur',
        jenis_kelamin = '$jenis_kelamin',
        jabatan_id = '$jabatan_id',
        alamat = '$alamat',
        no_hp = '$no_hp',
        foto = '$foto',
        status = '$status',
        tanggal_bergabung = '$tanggal_bergabung'
        WHERE id = '$id'");

    // Update atau Insert ke tabel rating
    if ($update) {
        $cek_rating = mysqli_query($conn, "SELECT * FROM rating WHERE karyawan_id = '$id'");
        if (mysqli_num_rows($cek_rating) > 0) {
            // Update rating
            $update_rating = mysqli_query($conn, "UPDATE rating SET nilai_rating = '$nilai_rating' WHERE karyawan_id = '$id'");
        } else {
            // Tambah rating baru
            $insert_rating = mysqli_query($conn, "INSERT INTO rating (karyawan_id, nilai) VALUES ('$id', '$nilai_rating')");
        }

        echo "success";
    } else {
        echo "Gagal mengupdate data karyawan: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}
?>
