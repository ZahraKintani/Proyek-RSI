<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'user_register');

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Menangani pencarian produk
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT p.*, u.Nama AS seller_name, u.profile_image FROM products p 
            JOIN users u ON p.penjual_id = u.id
            WHERE p.nama_barang LIKE '%$search%' OR p.deskripsi_barang LIKE '%$search%'";
} else {
    $sql = "SELECT p.*, u.Nama AS seller_name, u.profile_image FROM products p 
            JOIN users u ON p.penjual_id = u.id";
}

$result = $conn->query($sql);

// Menambahkan produk ke keranjang
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkout'])) {
    $product_id = $_POST['product_id'];

    // Menyimpan ID produk yang dipilih ke session
    $_SESSION['checkout_product'] = $product_id;

    // Redirect ke halaman checkout
    header("Location: checkout.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Utama - Marketplace</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Styling untuk card produk */
        .product-list {
        display: flex;
        flex-wrap: wrap;
        row-gap: 10px;
        gap: 5px; /* Mengatur jarak antar produk */
        padding: 50px;
        justify-content: space-between;
    }

    .product-card {
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin: 0; /* Menghapus margin agar lebih rapat */
        width: 240px; /* Menyesuaikan ukuran agar lebih proporsional */
        background-color: #fff;
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
        .product-img {
            width: 100%;
            height: 180px; /* Ukuran gambar lebih pas */
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
            transition: transform 0.3s ease;
        }
        .product-img:hover {
            transform: scale(1.05);
        }
        .product-card h3 {
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .product-card p {
            font-size: 0.95rem;
            color: #555;
            line-height: 1.5;
            margin-bottom: 10px;
        }
        .price {
            font-size: 1.1rem;
            font-weight: bold;
            color: #e67e22;
            margin-bottom: 10px;
        }
        .seller {
            font-size: 0.9rem;
            color: #7f8c8d;
            margin-top: 10px;
        }
        .seller-info {
            display: flex;
            justify-content: left;
            align-items: center;
            margin-bottom: 15px;
        }
        .seller-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .btn-chat, .btn-checkout, .negotiate-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 0.9rem;
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn-chat {
            background-color: #3498db;
            color: white;
        }
        .btn-checkout {
            background-color: #e67e22;
            color: white;
        }
        .negotiate-btn {
            background-color: #2ecc71;
            color: white;
        }
        .btn-chat:hover, .btn-checkout:hover, .negotiate-btn:hover {
            opacity: 0.8;
        }
        /* Responsif untuk tampilan mobile */
        @media (max-width: 768px) {
            .product-card {
                width: 100%;
                margin: 10px auto;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="hamburger" onmouseover="showSidebar()" onmouseout="hideSidebar()">
            <i class="fas fa-bars"></i> <!-- Ikon garis tiga -->
        </div>
        <div class="logo">
            
        </div>
        <div class="header-actions">
            <form action="home.php" method="GET" class="search-bar">
                <input type="text" name="search" placeholder="Cari produk..." value="<?php echo $search; ?>">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
            <a href="upload.php" class="upload-btn">Upload Produk</a>
            <a href="chattemplate.html" class="chat-btn"><i class="fas fa-comments"></i></a>
        </div>
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

    <main class="main-content">
    <h2>Produk Tersedia</h2>
    <div class="product-list">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='product-card'>";

            echo "<div class='seller-info'>";
            $profile_image = $row['profile_image'] ? $row['profile_image'] : 'default-profile.jpg';
            echo "<img src='uploads/" . $profile_image . "' alt='Profile Image'>";
            echo "<span>" . $row['seller_name'] . "</span>";
            echo "</div>";

            echo "<img src='uploads/" . $row['file_barang'] . "' alt='" . $row['nama_barang'] . "' class='product-img'>";
            echo "<h3>" . $row['nama_barang'] . "</h3>";
            echo "<p>" . $row['deskripsi_barang'] . "</p>";
            echo "<p class='price'>Harga: Rp" . number_format($row['harga_barang'], 0, ',', '.') . "</p>";

            echo "<form method='POST' action='home.php'>";
            echo "<input type='hidden' name='product_id' value='" . $row['id'] . "'>";
            echo "<button type='submit' name='checkout' class='btn-checkout'>Checkout</button>";
            echo "</form>";

            echo "<a href='negotiation.php?product_id=" . $row['id'] . "' class='negotiate-btn'>Negosiasi Harga</a>";
            echo "<a href='chattemplate.html' class='btn-chat'>Chat</a>";

            echo "</div>";
        }
    } else {
        echo "<p>Tidak ada produk tersedia.</p>";
    }
    ?>
</div>
    </main>

    <footer class="footer"> 
        <p>&copy; 2024 NPD. Semua hak cipta dilindungi.</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>

<?php
$conn->close();
?>