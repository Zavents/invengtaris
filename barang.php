<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$database = "inventaris";
$koneksi = new mysqli($host, $user, $password, $database);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

if (isset($_POST['add_barang'])) {
    $nama_barang = $_POST['nama_barang'];
    $harga_barang = $_POST['harga_barang'];
    
    $query = $koneksi->prepare("INSERT INTO barang (nama_barang, harga_barang) VALUES (?, ?)");
    $query->bind_param("si", $nama_barang, $harga_barang);
    $query->execute();
    header("Location: barang.php");
    exit();
}

if (isset($_POST['edit_barang'])) {
    $id_barang = $_POST['id_barang'];
    $nama_barang = $_POST['nama_barang'];
    $harga_barang = $_POST['harga_barang'];
    $stok = $_POST['stok'];
    
    $query = $koneksi->prepare("UPDATE barang SET nama_barang=?, harga_barang=?, stok=? WHERE id_barang=?");
    $query->bind_param("siii", $nama_barang, $harga_barang, $stok, $id_barang);
    $query->execute();
    header("Location: barang.php");
    exit();
}
if (isset($_GET['delete'])) {
    $id_barang = $_GET['delete'];
    
    $query = $koneksi->prepare("DELETE FROM barang WHERE id_barang=?");
    $query->bind_param("i", $id_barang);
    $query->execute();
    header("Location: barang.php");
    exit();
}

$result = $koneksi->query("SELECT * FROM barang");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


</head>
<body style="background-image: url(./img/bg.png);">
    <?php include('navbar.php'); ?>

    <div class="container mt-5">
        <h2>Barang Manajemen</h2>
        <button class="button mb-3" data-bs-toggle="modal" data-bs-target="#addModal">Tambah Barang</button>
        <table class="table table-bordered"onmouseover="this.style.borderColor='rgb(0, 162, 255)'" 
        onmouseout="this.style.borderColor='rgb(0, 0, 0)'" >
        <tr>
                <th>ID</th>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['id_barang'] ?></td>
                <td><?= $row['nama_barang'] ?></td>
                <td><?= $row['harga_barang'] ?></td>
                <td><?= $row['stok'] ?></td>
                <td>
                    <button class="button-edit btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id_barang'] ?>">Edit</button>
                    <a href="?delete=<?= $row['id_barang'] ?>" class="button-hapus btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <div class="modal fade" id="editModal<?= $row['id_barang'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Barang</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="post">
                            <div class="modal-body">
                                <input type="hidden" name="id_barang" value="<?= $row['id_barang'] ?>">
                                <label>Nama Barang</label>
                                <input type="text" name="nama_barang" class="form-control" value="<?= $row['nama_barang'] ?>" required>
                                <label>Harga</label>
                                <input type="number" name="harga_barang" class="form-control" value="<?= $row['harga_barang'] ?>" required>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="edit_barang" class="btn btn-success">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php } ?>
        </table>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <label>Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" required>
                        <label>Harga</label>
                        <input type="number" name="harga_barang" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="add_barang" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
