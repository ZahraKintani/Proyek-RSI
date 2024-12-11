<?php
$conn = new mysqli('localhost', 'root', '', 'user_register');

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$product_id = $_GET['product_id'];
if (!$product_id) {
    echo "Product ID tidak ditemukan.";
    exit;
}

// Menampilkan detail produk berdasarkan product_id
$query = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

$notification_message = '';
$show_notification = false; // Variabel untuk menampilkan notifikasi

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['proposed_price'])) {
    $proposed_price = $_POST['proposed_price'];

    if (is_numeric($proposed_price) && $proposed_price > 0) {
        // Simpan harga yang diajukan ke dalam database
        $user_id = 1; // Ganti dengan ID pengguna yang valid
        $stmt = $conn->prepare("INSERT INTO price_negotiations (product_id, proposed_price, user_id) VALUES (?, ?, ?)");
        $stmt->bind_param("idi", $product_id, $proposed_price, $user_id);
        $stmt->execute();
        
        $notification_message = "Harga yang diajukan: Rp" . number_format($proposed_price, 0, ',', '.');
        $show_notification = true; // Menampilkan notifikasi
    } else {
        $notification_message = "Harga yang diajukan tidak valid.";
        $show_notification = true; // Menampilkan notifikasi
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Negosiasi Harga - Marketplace</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<!-- Header -->
<header class="header">
    <div class="hamburger" onmouseover="showSidebar()" onmouseout="hideSidebar()">
        <i class="fas fa-bars"></i>
    </div>
    <div class="logo">
        <!-- Logo dapat ditambahkan di sini -->
    </div>
    <div class="header-actions">
        <a href="chattemplate.html" class="chat-btn"><i class="fas fa-comments"></i></a>
    </div>
</header>

<!-- Sidebar -->
<nav class="sidebar" id="sidebar" onmouseover="showSidebar()" onmouseout="hideSidebar()">
    <ul>
        <li><a href="profile.php"><i class="fas fa-user"></i> Profil</a></li>
        <li><a href="home.php"><i class="fas fa-home"></i> Beranda</a></li>
        <li><a href="location.php"><i class="fas fa-map-marker-alt"></i> Range Lokasi Produk</a></li>
        <li><a href="report.php"><i class="fas fa-exclamation-circle"></i> Laporkan Masalah</a></li>
        <li><a href="orders.php"><i class="fas fa-box"></i> Pesanan</a></li>
    </ul>
</nav>

<!-- Konten Negosiasi Harga -->
<main class="main-content-nego">
    <h2>Negosiasi Harga untuk Produk: <?php echo $product['nama_barang']; ?></h2>

    <form action="negotiation.php?product_id=<?php echo $product_id; ?>" method="POST" class="nego-form">
        <label for="proposed_price">Harga yang diajukan:</label>
        <input type="number" id="proposed_price" name="proposed_price" required>
        <button type="submit">Ajukan Harga</button>
    </form>

    <!-- Kotak Notifikasi Pop-up -->
    <?php if ($show_notification) { ?>
        <div class="notification-box show">
            <p><?php echo $notification_message; ?></p>
        </div>
    <?php } ?>

    <br><br>
    <a href="home.php">Kembali ke Halaman Utama</a>
</main>

<footer class="footer">
    <p>&copy; 2024 Marketplace. Semua hak cipta dilindungi.</p>
</footer>

<script src="script.js"></script>

</body>
</html>
