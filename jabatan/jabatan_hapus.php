<?php
include '../koneksi.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = mysqli_query($conn, "DELETE FROM jabatan WHERE id = $id");
    echo $query ? "success" : "failed";
}
?>
