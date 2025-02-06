<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    die("Error: User session not set. Pastikan pengguna sudah login.");
}

$host = "localhost";
$user = "root";
$password = "";
$database = "inventaris";
$koneksi = new mysqli($host, $user, $password, $database);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Fetch all transactions from laporan table
$logs = $koneksi->query("SELECT * FROM laporan_inventaris ORDER BY tanggal DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <h2>Laporan Transaksi</h2>
        
        <table class="table">
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Barang</th>
                    <th>Jenis Aktivitas</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                    <th>User</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($log = $logs->fetch_assoc()) {
                    // Fetch item name for the transaction log
                    $barang_query = $koneksi->prepare("SELECT nama_barang FROM barang WHERE id_barang = ?");
                    $barang_query->bind_param("i", $log['id_barang']);
                    $barang_query->execute();
                    $barang_result = $barang_query->get_result();
                    $barang = $barang_result->fetch_assoc();

                    // Fetch user name for the transaction log
                    $user_query = $koneksi->prepare("SELECT username FROM users WHERE id_user = ?");
                    $user_query->bind_param("i", $log['id_user']);
                    $user_query->execute();
                    $user_result = $user_query->get_result();
                    $user = $user_result->fetch_assoc();
                ?>
                    <tr>
                        <td><?= $log['id_laporan'] ?></td>
                        <td><?= $barang['nama_barang'] ?></td>
                        <td><?= ucfirst($log['tipe_aktivitas']) ?></td>
                        <td><?= $log['jumlah'] ?></td>
                        <td><?= $log['tanggal'] ?></td>
                        <td><?= $user['username'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
