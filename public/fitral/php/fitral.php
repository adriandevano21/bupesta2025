<?php
$host = "localhost"; // alamat host database
$username = "bupestaw_viewer"; // username database
$password = "sOXW(haQSgJ^"; // password database
$database = "bupestaw_bupesta"; // nama database

$conn = mysqli_connect($host, $username, $password, $database);

// Periksa koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>