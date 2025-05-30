<?php
// Konfigurasi koneksi ke database
$host     = "localhost";    // Host database (default: localhost)
$username = "root";         // Username MySQL (default: root)
$password = "";             // Password MySQL (default: kosong di XAMPP)
$database = "management_gaji";      // Nama database

// Membuat koneksi
$conn = mysqli_connect($host, $username, $password, $database);

// Mengecek koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
