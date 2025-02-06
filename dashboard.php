<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Include Navbar -->
<?php include 'navbar.php'; ?>

<div class="container text-white text-center mt-5">
    <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
    <p>Choose a menu above to manage the inventory system.</p>
</div>


    <style>
        body { 
            font-family: Arial, sans-serif;
            background: url('./img/bg.png') no-repeat center center fixed;
            background-size: cover;
        }
        .navbar { background: #333; padding: 10px; }
        .navbar a { color: white; margin: 10px; text-decoration: none; }
        .navbar a:hover { text-decoration: underline; }
        .container { padding: 20px; }
    </style>


    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
