<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'user_register');

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mengambil produk yang dipilih dari session
$product_id = $_SESSION['checkout_product'] ?? null;
$product = null;

if ($product_id) {
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
}

// Jika produk tidak ditemukan, tampilkan error
if (!$product) {
    echo "<p>Produk tidak ditemukan. Silakan pilih produk yang valid.</p>";
    exit();
}

// Proses form checkout
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_checkout'])) {
    $user_id = $_SESSION['user_id'] ?? null; // Pastikan user_id tersimpan dalam session
    $alamat = $_POST['alamat'];
    $phone = $_POST['phone'];
    $payment_method = $_POST['payment_method'];

    // Hitung total harga produk
    $total_price = $product['harga_barang']; // Ambil harga dari produk yang dipilih

    // Simpan pesanan ke tabel orders
    $sql_order = "INSERT INTO orders (user_id, total_amount, alamat, phone, payment_method, order_date, status) VALUES (?, ?, ?, ?, ?, NOW(), 'pending')";
    $stmt_order = $conn->prepare($sql_order);
    $stmt_order->bind_param("idsss", $user_id, $total_price, $alamat, $phone, $payment_method);

    if ($stmt_order->execute()) {
        // Mendapatkan ID order yang baru saja dibuat
        $order_id = $stmt_order->insert_id;

        // Simpan item pesanan ke tabel order_items
        $sql_order_item = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, 1)";
        $stmt_order_item = $conn->prepare($sql_order_item);
        $stmt_order_item->bind_param("ii", $order_id, $product_id);
        $stmt_order_item->execute();

        // Menampilkan konfirmasi pembelian
        echo "
        <html lang='id'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Konfirmasi Pembelian</title>
            <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css'>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f8f9fa;
                    margin: 0;
                    padding: 0;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                }
                .confirmation-box {
                    background-color: #fff;
                    border-radius: 10px;
                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                    max-width: 600px;
                    width: 100%;
                    padding: 30px;
                    text-align: center;
                }
                .confirmation-box h3 {
                    color: #28a745;
                    margin-bottom: 10px;
                }
                .confirmation-box p {
                    color: #333;
                    margin: 5px 0;
                }
                .confirmation-box .total {
                    margin: 15px 0;
                    font-size: 1.2rem;
                    font-weight: bold;
                    color: #e67e22;
                }
                .confirmation-box button {
                    background-color: #28a745;
                    color: #fff;
                    border: none;
                    padding: 12px 25px;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 16px;
                    font-weight: bold;
                    transition: background-color 0.3s;
                }
                .confirmation-box button:hover {
                    background-color: #218838;
                }
            </style>
        </head>
        <body>
            <div class='confirmation-box'>
                <h3>Terima kasih! Pesanan Anda telah diproses.</h3>
                <p><strong>Produk:</strong> {$product['nama_barang']}</p>
                <p><strong>Harga:</strong> {$product['harga_barang']}</p>
                <p><strong>Alamat Pengiriman:</strong> {$alamat}</p>
                <p><strong>Metode Pembayaran:</strong> {$payment_method}</p>
                <button onclick='window.location.href=\"orders.php\"'>Lihat Riwayat Pesanan</button>
            </div>
        </body>
        </html>";
        unset($_SESSION['checkout_product']);
        exit();
    } else {
        echo "<p>Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 30px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .product-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .product-info img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
        }
        .product-info div {
            flex: 1;
            margin-left: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        input, textarea, select, button {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background-color: #28a745;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <header class="header">
        <div class="hamburger" onmouseover="showSidebar()" onmouseout="hideSidebar()">
            <i class="fas fa-bars"></i> <!-- Ikon garis tiga -->
        </div>
        <div class="logo"></div>
        <a href="chattemplate.html" class="chat-btn"><i class="fas fa-comments"></i></a>
    </header>

    <nav class="sidebar" id="sidebar" onmouseover="showSidebar()" onmouseout="hideSidebar()">
        <ul>
            <li><a href="profile.php"><i class="fas fa-user"></i> Profil</a></li>
            <li><a href="home.php"><i class="fas fa-home"></i> Beranda</a></li>
            <li><a href="location.php"><i class="fas fa-map-marker-alt"></i> Range Lokasi Produk</a></li>
            <li><a href="LaporkanMasalah.html"><i class="fas fa-exclamation-circle"></i> Laporkan Masalah</a></li>
            <li><a href="orders.php"><i class="fas fa-box"></i> Pesanan</a></li>
        </ul>
    </nav>

    <div class="container">
        <h2>Isi Data Pengiriman</h2>

        <div class="product-info">
        <img src="uploads/<?php echo $product['file_barang']; ?>" alt="<?php echo $product['nama_barang']; ?>">
            <div>
                <p><strong>Nama Produk:</strong> <?= $product['nama_barang']; ?></p>
                <p><strong>Harga:</strong> <?= isset($product['harga_barang']) ? number_format($product['harga_barang'], 0, ',', '.') : 'N/A'; ?> IDR</p>
            </div>
        </div>

        <form method="POST" action="checkout.php">
            <label for="alamat">Alamat Pengiriman:</label>
            <textarea name="alamat" id="alamat" required></textarea>

            <label for="phone">Nomor Telepon:</label>
            <input type="tel" name="phone" id="phone" required>

            <label for="payment_method">Metode Pembayaran:</label>
            <select name="payment_method" id="payment_method" required>
                <option value="bank_transfer">Transfer Bank</option>
                <option value="credit_card">Kartu Kredit</option>
                <option value="cash_on_delivery">Cash on Delivery</option>
            </select>

            <button type="submit" name="confirm_checkout">Konfirmasi Pembelian</button>
        </form>
    </div>

    <script>
        function showSidebar() {
            document.getElementById('sidebar').style.display = 'block';
        }

        function hideSidebar() {
            document.getElementById('sidebar').style.display = 'none';
        }
    </script>

<footer class="footer"> 
        <p>&copy; 2024 Marketplace. Semua hak cipta dilindungi.</p>
    </footer>

    <script src="script.js"></script>

</body>
</html>

