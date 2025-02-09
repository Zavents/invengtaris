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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $query = $koneksi->prepare("SELECT * FROM users WHERE username = ?");
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {

            $_SESSION['id_user'] = $user['id_user'];
            
            $_SESSION['username'] = $user['username'];
            $_SESSION['level'] = $user['level'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('./img/bg.png') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

            .login-container {
        border: 5px solid rgba(92, 142, 216, 0.5);
        padding: 30px;
        border-radius: 10px;
        border-radius: 2px;
        width: 350px;
        box-shadow: 5px 5px 0px rgba(47, 130, 255, 0.5);
        transition: border-color 0.3s;
        }

        .login-container:hover {
        border-color: #757575;
        }
        .button {
                    --bg: #000;
                --hover-bg: rgba(0, 101, 253, 0.66);
                --hover-text: #000;
                color: #fff;
                cursor: pointer;
                border: 1px solid var(--bg);
                border-radius: 4px;
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
<body>
    <div class="login-container" 
    onmouseover="this.style.borderColor='rgba(109, 247, 224, 0.65)'" 
    onmouseout="this.style.borderColor='rgba(0, 101, 253, 0.66)'" >
        <h2 class="text-center">Login</h2>
        <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
        <form method="post" action="">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" name="login" class="button w-100">Login</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
