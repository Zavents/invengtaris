<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "inventaris");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data
$barangResult = $conn->query("SELECT COUNT(*) as total_barang, SUM(stok) as total_stok FROM barang");
$barang = $barangResult->fetch_assoc();

$pembelianResult = $conn->query("SELECT COUNT(*) as total_pembelian, SUM(jumlah) as total_items FROM laporan_inventaris");
$pembelian = $pembelianResult->fetch_assoc();

$supplierResult = $conn->query("SELECT COUNT(*) as total_supplier FROM supplier");
$supplier = $supplierResult->fetch_assoc();

$userResult = $conn->query("SELECT COUNT(*) as total_user FROM users");
$user = $userResult->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('./img/bg.png') no-repeat center center fixed;
            background-size: cover;
            color: white;
        }
        .dashboard-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            justify-items: center;
        }


        .card {
        --bg: #fff !important;
        --hover-bg: rgba(0, 101, 253, 0.66) !important;
        --hover-text: #000 !important;
        color: #000 !important;
        cursor: pointer !important;
        border: 3px solid black !important;
        background: var(--bg) !important;
        transition: 0.2s !important;
        
        padding: 0.8em 2em !important;
        width: 250px !important;
        min-height: 150px !important;

        display: flex !important;
        flex-direction: column !important;
        justify-content: center !important;
        align-items: center !important;
    }
    .card:hover {
        color: var(--hover-text) !important;
        transform: translate(-0.25rem, -0.25rem) !important;
        background: var(--hover-bg) !important;
        box-shadow: 0.25rem 0.25rem var(--bg) !important;
    }

        .card:active {
        transform: translate(0) !important;
        box-shadow: none !important;
    }


        .counter {
            font-size: 1.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body>

<!-- Include Navbar -->
<?php include 'navbar.php'; ?>

<div class="container text-center mt-5">
    <h2>Halo, <?php echo $_SESSION['username']; ?>!</h2>
    <p>Berikut data dari inventaris anda.</p>
</div>

<div class="container mt-1">
    <div class="dashboard-container">
        <div class="card p-3 mt-4" onmouseover="this.style.borderColor='rgb(0, 162, 255)'" 
            onmouseout="this.style.borderColor='rgb(0, 0, 0)'" >
            <h5 class="card-title text-bold"><strong>Barang</strong></h5>
            <p class="card-text">Total Barang: <span class="counter" id="totalBarang">0</span></p>
            <p class="card-text">Stok Barang: <span class="counter" id="totalStok">0</span></p>
        </div>

        <div class="card p-3 mt-4" onmouseover="this.style.borderColor='rgb(0, 162, 255)'" 
            onmouseout="this.style.borderColor='rgb(0, 0, 0)'" >
            <h5 class="card-title text-bold"><strong>Pembelian</strong></h5>
            <p class="card-text">Transaksi: <span class="counter" id="totalPembelian">0</span></p>
            <p class="card-text">Total Barang Dibeli: <span class="counter" id="totalItems">0</span></p>
        </div>

        <div class="card p-3 mt-4" onmouseover="this.style.borderColor='rgb(0, 162, 255)'" 
            onmouseout="this.style.borderColor='rgb(0, 0, 0)'" >
            <h5 class="card-title text-bold"><strong>Supplier</strong></h5>
            <p class="card-text">Total Supplier: <span class="counter" id="totalSupplier">0</span></p>
        </div>

        <div class="card p-3 mt-4" onmouseover="this.style.borderColor='rgb(0, 162, 255)'" 
            onmouseout="this.style.borderColor='rgb(0, 0, 0)'" >
            <h5 class="card-title text-bold"><strong>Data User</strong> </h5>
            <p class="card-text">Total User : <span class="counter" id="totalUser">0</span></p>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function animateCounter(id, target) {
        let count = 0;
        let interval = setInterval(() => {
            if (count >= target) {
                clearInterval(interval);
            } else {
                count++;
                document.getElementById(id).innerText = count;
            }
        }, 10);
    }

    document.addEventListener("DOMContentLoaded", function() {
        animateCounter("totalBarang", <?php echo $barang['total_barang']; ?>);
        animateCounter("totalStok", <?php echo $barang['total_stok']; ?>);
        animateCounter("totalPembelian", <?php echo $pembelian['total_pembelian']; ?>);
        animateCounter("totalItems", <?php echo $pembelian['total_items']; ?>);
        animateCounter("totalSupplier", <?php echo $supplier['total_supplier']; ?>);
        animateCounter("totalUser", <?php echo $user['total_user']; ?>);
    });
</script>
</body>
</html>
