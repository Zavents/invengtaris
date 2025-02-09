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
$success_message = "";
$error_message = "";

if (isset($_POST['add'])) {
    $id_supplier = $_POST['id_supplier'];
    $tanggal_pembelian = date('Y-m-d', strtotime($_POST['tanggal_pembelian']));
    $total_harga = $_POST['total_harga'];
    $barang_ids = $_POST['barang'];
    $jumlah_barang = $_POST['jumlah_barang'];

    if (empty($barang_ids) || empty($jumlah_barang)) {
        echo "Please select at least one item and specify the quantity.";
        exit();
    }
    
    $id_user = $_SESSION['id_user'];
    $query = $koneksi->prepare("INSERT INTO pembelian (id_user, id_supplier, tanggal_pembelian, total_harga) VALUES (?, ?, ?, ?)");
    $query->bind_param("iiss", $id_user, $id_supplier, $tanggal_pembelian, $total_harga);
    
    if (!$query->execute()) {
        echo "Error: " . $query->error;
        exit();
    }
    
    $id_pembelian = $query->insert_id;

    foreach ($barang_ids as $index => $barang_id) {
        $jumlah = intval($jumlah_barang[$index]);
        
$id_user = $_SESSION['id_user']; 

$query = $koneksi->prepare("REPLACE INTO detail_pembelian (id_pembelian, id_barang, jumlah_barang, id_user) VALUES (?, ?, ?, ?)");

$query->bind_param("iiii", $id_pembelian, $barang_id, $jumlah, $id_user);

$query->execute();

        $query = $koneksi->prepare("SELECT stok FROM barang WHERE id_barang = ?");
        $query->bind_param("i", $barang_id);
        $query->execute();
        $result = $query->get_result();
        $row = $result->fetch_assoc();


        $query = $koneksi->prepare("UPDATE barang SET stok = stok + ? WHERE id_barang = ?");
        $query->bind_param("ii", $jumlah, $barang_id);
        if (!$query->execute()) {
            echo "Error: " . $query->error;
            exit();
        }

        $query = $koneksi->prepare("INSERT INTO laporan_inventaris (id_barang, id_user, id_supplier, jumlah, tanggal) VALUES (?, ?, ?, ?, ?)");
        $tanggal = date('Y-m-d H:i:s');
        $query->bind_param("iiiss", $barang_id, $id_user, $id_supplier, $jumlah, $tanggal);
        if (!$query->execute()) {
            echo "Error logging transaction: " . $query->error;
            exit();
        }
    }
    
    header("Location: pembelian.php");
    exit();
}

$suppliers = $koneksi->query("SELECT * FROM supplier");
$barang = $koneksi->query("SELECT * FROM barang");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembelian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
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

        .form-control, .button {
            margin-bottom: 15px;
        }

        #barang-container {
            margin-top: 20px;
        }
    </style>
    <script>
        function updateTotal() {
            let total = 0;
            document.querySelectorAll('.barang').forEach((select, index) => {
                let harga = parseFloat(select.selectedOptions[0].getAttribute('data-harga')) || 0;
                let jumlah = parseInt(document.querySelectorAll('.jumlah')[index].value) || 0;
                total += harga * jumlah;
            });
            document.getElementById('total_harga').value = total;
        }
    </script>
</head>
<body style="background-image: url(./img/bg.png);">
    
<?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <h2>Pembelian</h2>
        <div class="card p-4 mb-4"
            onmouseover="this.style.borderColor='rgb(0, 162, 255)'" 
            onmouseout="this.style.borderColor='rgb(0, 0, 0)'" >
            <form method="post">
                <label>Pilih Barang</label>
                <div id="barang-container">
                    <div>
                        <select name="barang[]" class="barang form-control" onchange="updateTotal()" required>
                            <?php while ($brg = $barang->fetch_assoc()) { ?>
                                <option value="<?= $brg['id_barang'] ?>" data-harga="<?= $brg['harga_barang'] ?>">
                                    <?= $brg['nama_barang'] ?> (Rp<?= $brg['harga_barang'] ?>)
                                </option>
                            <?php } ?>
                        </select>
                        <input type="number" name="jumlah_barang[]" class="jumlah form-control" placeholder="Jumlah Barang" oninput="updateTotal()" required>
                    </div>
                </div>

                <label>Tanggal Pembelian</label>
                <input type="date" name="tanggal_pembelian" class="form-control" required>
                
                <label>Total Harga</label>
                <input type="number" id="total_harga" name="total_harga" placeholder="Total Harga" class="form-control" readonly>
                
                <label>Nama Supplier</label>
                <select name="id_supplier" class="form-control" required>
                    <?php while ($sup = $suppliers->fetch_assoc()) { ?>
                        <option value="<?= $sup['id_supplier'] ?>"><?= $sup['nama_supplier'] ?></option>
                    <?php } ?>
                </select>

                <button type="submit" name="add" class="button">Tambah Pembelian</button>
            </form>
            
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
