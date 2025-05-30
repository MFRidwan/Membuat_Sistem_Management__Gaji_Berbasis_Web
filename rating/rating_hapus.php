<?php 

include '../koneksi.php';

// rating_hapus.php
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = mysqli_query($conn, "DELETE FROM rating WHERE id = $id");
    echo $query ? "success" : "failed";
}

?>