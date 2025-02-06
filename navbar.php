<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Supplier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add Poppins font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        /* Customize the scrollbar for modern browsers */
::-webkit-scrollbar {
    width: 4px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1; /* Light gray for track */
}

::-webkit-scrollbar-thumb {
    background-color: #007bff; /* Blue, similar to the background */
    border-radius: 10px;
    border: 1px solidrgba(241, 241, 241, 0); /* Border color for contrast */
}

::-webkit-scrollbar-thumb:hover {
    background-color: #0056b3; /* Darker blue for hover effect */
}

    </style>
    
<style>        .navbar { background: #333; padding: 10px; }
        .navbar a { color: white; margin: 10px; text-decoration: none; }
        .navbar a:hover { text-decoration: underline; }
        .container { padding: 20px; }</style>
        
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
    .nav{
        text-decoration: none !important;
    }

</style>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php"><img src="./img/loogo.png" width="75" alt=""></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="barang.php">Barang</a></li>
                <li class="nav-item"><a class="nav-link" href="pembelian.php">Pembelian</a></li>
                <li class="nav-item"><a class="nav-link" href="penjualan.php">Penjualan</a></li>
                <li class="nav-item"><a class="nav-link" href="supplier.php">Supplier</a></li>

                <!-- Show "Data User" only for admin users -->
                <?php if (isset($_SESSION['level']) && $_SESSION['level'] == 'administrator'): ?>
                    <li class="nav-item"><a class="nav-link" href="data_user.php">Data User</a></li>
                <?php endif; ?>
            </ul>
        </div>
        
        <!-- Display Username and Logout Button -->
        <div class="d-flex align-items-center">
            <span class="text-white me-3">
                <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?>
            </span>
            <a href="login.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</nav>

