<?php
$host = "localhost"; // Ganti dengan host database Anda jika perlu
$user = "root"; // Ganti dengan username database Anda
$password = ""; // Ganti dengan password database Anda
$database = "inventaris";

// Membuat koneksi
$koneksi = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
} else {
    echo "Koneksi berhasil";
}
?>
