<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$database = "inventaris";

$koneksi = new mysqli($host, $user, $password, $database);

if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_supplier'])) {
    $nama_supplier = $_POST['nama_supplier'];
    $alamat = $_POST['alamat'];
    $nomor_telepon = $_POST['nomor_telepon'];

    $query = $koneksi->prepare("INSERT INTO supplier (nama_supplier, alamat, nomor_telepon) VALUES (?, ?, ?)");
    $query->bind_param("sss", $nama_supplier, $alamat, $nomor_telepon);
    $query->execute();

    if ($query->affected_rows > 0) {
        $success = "Supplier successfully added!";
    } else {
        $error = "Failed to add supplier. Try again.";
    }
}

if (isset($_GET['delete'])) {
    $id_supplier = $_GET['delete'];
    $query = $koneksi->prepare("DELETE FROM supplier WHERE id_supplier = ?");
    $query->bind_param("i", $id_supplier);
    $query->execute();

    if ($query->affected_rows > 0) {
        header("Location: supplier.php");
        exit();
    } else {
        $error = "Failed to delete supplier.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_supplier'])) {
    $id_supplier = $_POST['id_supplier'];
    $nama_supplier = $_POST['nama_supplier'];
    $alamat = $_POST['alamat'];
    $nomor_telepon = $_POST['nomor_telepon'];

    $query = $koneksi->prepare("UPDATE supplier SET nama_supplier=?, alamat=?, nomor_telepon=? WHERE id_supplier=?");
    $query->bind_param("sssi", $nama_supplier, $alamat, $nomor_telepon, $id_supplier);
    $query->execute();

    if ($query->affected_rows > 0) {
        $success = "Supplier updated successfully!";
    } else {
        $error = "Failed to update supplier.";
    }
}

$query = "SELECT id_supplier, nama_supplier, alamat, nomor_telepon FROM supplier";
$result = $koneksi->query($query);

if ($result === false) {
    $error = "Failed to retrieve supplier from the database.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage supplier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-image: url(./img/bg.png);">
<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <h2 class="text-center">Supplier</h2>

    <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
    <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>

    <div class="card p-4 mb-4"
     onmouseover="this.style.borderColor='rgb(0, 162, 255)'" 
        onmouseout="this.style.borderColor='rgb(0, 0, 0)'" >
        <h3>Tambahkan Supplier baru</h3>
        <form method="post" action="">
            <div class="mb-3">
                <label class="form-label">Nama Supplier </label>
                <input type="text" name="nama_supplier" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <input type="text" name="alamat" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Nomor Telepon</label>
                <input type="text" name="nomor_telepon" class="form-control" required>
            </div>
            <button type="submit" name="add_supplier" class="button">Tambahkan Supplier</button>
        </form>
    </div>

    <div class="mt-4">
        <h3>list Supplier Sekarang   </h3>
        <table class="table table-striped table-bordered"
     onmouseover="this.style.borderColor='rgb(0, 162, 255)'" 
        onmouseout="this.style.borderColor='rgb(0, 0, 0)'" >
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nama Supplier</th>
                    <th>Alamat</th>
                    <th>Nomor Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($result) && $result->num_rows > 0) : ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo $row['id_supplier']; ?></td>
                            <td><?php echo $row['nama_supplier']; ?></td>
                            <td><?php echo $row['alamat']; ?></td>
                            <td><?php echo $row['nomor_telepon']; ?></td>
                            <td>
                                <a href="edit_supplier.php?id=<?php echo $row['id_supplier']; ?>" class="button-edit btn-sm">Edit</a>
                                <a href="?delete=<?php echo $row['id_supplier']; ?>" class="button-hapus btn-sm" onclick="return confirm('Are you sure?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center">Supplier Tidak Ditemukan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
