<?php
$host = "localhost";
$password = ""; 
$database = "inventaris";

$koneksi = new mysqli($host, $user, $password, $database);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
} else {
    echo "Koneksi berhasil";
}
?>
