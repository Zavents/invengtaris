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

$logs = $koneksi->query("SELECT * FROM laporan_inventaris ORDER BY tanggal DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function printTable() {
            var printContents = document.getElementById("reportTable").outerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = "<html><head><title>Print</title></head><body>" + printContents + "</body></html>";
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
</head>
<body style="background-image:url(./img/bg.png)">
<?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <h2>Laporan Transaksi</h2>
        <button class="button  mb-3" onclick="printTable()">Print Laporan</button>
        <table class="table" id="reportTable" onmouseover="this.style.borderColor='rgb(0, 162, 255)'" onmouseout="this.style.borderColor='rgb(0, 0, 0)'">
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                    <th>Supplier</th>
                    <th>User</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($log = $logs->fetch_assoc()) {
                    $barang_query = $koneksi->prepare("SELECT nama_barang FROM barang WHERE id_barang = ?");
                    $barang_query->bind_param("i", $log['id_barang']);
                    $barang_query->execute();
                    $barang_result = $barang_query->get_result();
                    $barang = $barang_result->fetch_assoc();

                    $user_query = $koneksi->prepare("SELECT username FROM users WHERE id_user = ?");
                    $user_query->bind_param("i", $log['id_user']);
                    $user_query->execute();
                    $user_result = $user_query->get_result();
                    $user = $user_result->fetch_assoc();

                    $supplier_name = "Tidak Ada";
                    if ($log['id_supplier']) {
                        $supplier_query = $koneksi->prepare("SELECT nama_supplier FROM supplier WHERE id_supplier = ?");
                        $supplier_query->bind_param("i", $log['id_supplier']);
                        $supplier_query->execute();
                        $supplier_result = $supplier_query->get_result();
                        $supplier = $supplier_result->fetch_assoc();
                        if ($supplier) {
                            $supplier_name = $supplier['nama_supplier'];
                        }
                    }
                ?>
                    <tr>
                        <td><?= $log['id_laporan'] ?></td>
                        <td><?= $barang['nama_barang'] ?></td>
                        <td><?= $log['jumlah'] ?></td>
                        <td><?= $log['tanggal'] ?></td>
                        <td><?= $supplier_name ?></td>
                        <td><?= $user['username'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
