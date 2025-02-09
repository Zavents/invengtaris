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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $name = $_POST['name'];
    $level = $_POST['level'];

    if ($password != $confirm_password) {
        $error = "Password and confirmation do not match.";
    } else {
        $query = $koneksi->prepare("SELECT * FROM users WHERE username = ?");
        $query->bind_param("s", $username);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            $error = "Username already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = $koneksi->prepare("INSERT INTO users (username, name, password, level) VALUES (?, ?, ?, ?)");
            $query->bind_param("ssss", $username, $name, $hashed_password, $level);
            $query->execute();

            if ($query->affected_rows > 0) {
                $success = "User successfully registered!";
            } else {
                $error = "An error occurred. Try again.";
            }
        }
    }
}
if (isset($_GET['delete'])) {
    $id_user = $_GET['delete'];
    $query = $koneksi->prepare("DELETE FROM users WHERE id_user = ?");
    $query->bind_param("i", $id_user);
    $query->execute();

    if ($query->affected_rows > 0) {
        header("Location: data_user.php");
        exit();
    } else {
        $error = "Failed to delete user.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_user'])) {
    $id_user = $_POST['id_user'];
    $username = $_POST['username'];
    $name = $_POST['name'];
    $level = $_POST['level'];

    $query = $koneksi->prepare("UPDATE users SET username=?, name=?, level=? WHERE id_user=?");
    $query->bind_param("sssi", $username, $name, $level, $id_user);
    $query->execute();

    if ($query->affected_rows > 0) {
        $success = "User updated successfully!";
    } else {
        $error = "Failed to update user.";
    }
}

$query = "SELECT id_user, username, name, level FROM users";
$result = $koneksi->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .button:hover {
        color: var(--hover-text);
        transform: translate(-0.25rem, -0.25rem);
        background: var(--hover-bg);
        box-shadow: 0.25rem 0.25rem var(--bg);
    }
        .button:active {
        transform: translate(0);
        box-shadow: none;
    }
    
    .card {
        border: 5px solid rgb(0, 0, 0);
        padding: 30px;
        box-shadow: 5px 5px 0px rgba(0, 0, 0, 0.5);
        transition: border-color 0.3s;
    }
    .table {
        border: 5px solid rgb(0, 0, 0);
        padding: 30px;
        box-shadow: 5px 5px 0px rgba(0, 0, 0, 0.5);
        transition: border-color 0.3s;
    }
    .button {
            --bg: #000;
        --hover-bg: rgba(0, 101, 253, 0.66);
        --hover-text: #000;
        color: #fff;
        cursor: pointer;
        border: 1px solid var(--bg);
        
        padding: 0.8em 2em;
        background: var(--bg);
        transition: 0.2s;
    }
    .button:hover {
        color: var(--hover-text);
        transform: translate(-0.25rem, -0.25rem);
        background: var(--hover-bg);
        box-shadow: 0.25rem 0.25rem var(--bg);
    }

        .button:active {
        transform: translate(0);
        box-shadow: none;
    }

</style>

</head>
<body style="background-image: url(./img/bg.png);">
<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <h2 class="text-center">Data User</h2>

    <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
    <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>

    <!-- Add New User Form -->
    <div class="card p-4 mb-4" onmouseover="this.style.borderColor='rgb(0, 162, 255)'" onmouseout="this.style.borderColor='rgb(0, 0, 0)'">
    <h3>Tambahkan User Baru</h3>
    <form method="post" action="">
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="username" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" placeholder="fullname" required>
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="password" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="confirm password" required>
            </div>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Access Level</label>
            <select name="level" class="form-select" required>
                <option value="petugas">Petugas</option>
                <option value="administrator">Administrator</option>
            </select>
        </div>
        
        <button type="submit" name="register" class="button">Register User</button>
    </form>
</div>


    <!-- Show Current Users in a Table -->
    <div class="mt-4">
        <h3>Current Users</h3>
        <table class="table table-striped table-bordered"
        onmouseover="this.style.borderColor='rgb(0, 162, 255)'" 
        onmouseout="this.style.borderColor='rgb(0, 0, 0)'" >
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Akses User</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $row['id_user']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo ucfirst($row['level']); ?></td>
                        <td>
                            <a href="edit_user.php?id=<?php echo $row['id_user']; ?>" class="button-edit btn-sm">Edit</a>
                            <a href="?delete=<?php echo $row['id_user']; ?>" class="button-hapus btn-sm" onclick="return confirm('Are you sure?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
