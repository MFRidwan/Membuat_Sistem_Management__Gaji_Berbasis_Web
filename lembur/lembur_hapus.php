<?php
include '../koneksi.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $delete = mysqli_query($conn, "DELETE FROM lembur WHERE id = '$id'");
    echo $delete ? "success" : "failed";

}
?>

