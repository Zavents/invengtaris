<?php
session_start();

// Check if the user is logged in and an administrator
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
    die("Koneksi gagal: " . $koneksi->connect_error);
}

if (isset($_GET['id'])) {
    $id_user = $_GET['id'];

    // Fetch the user data to prefill the form
    $query = $koneksi->prepare("SELECT * FROM users WHERE id_user = ?");
    $query->bind_param("i", $id_user);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "User not found.";
        exit();
    }
}

// Handle the update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_user'])) {
    $username = $_POST['username'];
    $name = $_POST['name'];
    $level = $_POST['level'];

    $query = $koneksi->prepare("UPDATE users SET username=?, name=?, level=? WHERE id_user=?");
    $query->bind_param("sssi", $username, $name, $level, $id_user);
    $query->execute();

    if ($query->affected_rows > 0) {
        $success = "User updated successfully!";
        header("Location: data_user.php");
        exit();
    } else {
        $error = "Failed to update user.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-image: url(./img/bg.png);">
<!-- Include Navbar -->
<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <h2 class="text-center">Edit User</h2>

    <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
    <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>

    <div class="card p-4 mb-4"
     onmouseover="this.style.borderColor='rgb(0, 162, 255)'" 
        onmouseout="this.style.borderColor='rgb(0, 0, 0)'" >
        <h3>Edit User Details</h3>
        <form method="post" action="">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $user['username']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo $user['name']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Access Level</label>
                <select name="level" class="form-select" required>
                    <option value="petugas" <?php echo ($user['level'] == 'petugas') ? 'selected' : ''; ?>>Petugas</option>
                    <option value="administrator" <?php echo ($user['level'] == 'administrator') ? 'selected' : ''; ?>>Administrator</option>
                </select>
            </div>
            <button type="submit" name="edit_user" class="button ">Update User</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
