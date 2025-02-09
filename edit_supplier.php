<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['level'] != 'administrator') {
    echo "<div style='text-align:center; margin-top:50px; font-size:24px; color:red;'>You don't have access to this page.</div>";
    exit();
}

$host = "localhost";
$user = "root";
$password = "";
$database = "inventaris";

$koneksi = new mysqli($host, $user, $password, $database);

if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: supplier.php");
    exit();
}

$id_supplier = $_GET['id'];

$query = $koneksi->prepare("SELECT * FROM supplier WHERE id_supplier = ?");
$query->bind_param("i", $id_supplier);
$query->execute();
$result = $query->get_result();

if ($result->num_rows == 0) {
    header("Location: supplier.php");
    exit();
}

$supplier = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_supplier'])) {
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Supplier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-image: url(./img/bg.png);">
<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <h2 class="text-center">Edit Supplier</h2>

    <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
    <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>

    <div class="card p-4 mb-4"
     onmouseover="this.style.borderColor='rgb(0, 162, 255)'" 
        onmouseout="this.style.borderColor='rgb(0, 0, 0)'" >
        
        <h3>Edit Supplier</h3>
        <form method="post" action="">
            <div class="mb-3">
                <label class="form-label">Supplier Name</label>
                <input type="text" name="nama_supplier" class="form-control" value="<?php echo $supplier['nama_supplier']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" name="alamat" class="form-control" value="<?php echo $supplier['alamat']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" name="nomor_telepon" class="form-control" value="<?php echo $supplier['nomor_telepon']; ?>" required>
            </div>
            <button type="submit" name="edit_supplier" class="button">Update Supplier</button>
        </form>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
