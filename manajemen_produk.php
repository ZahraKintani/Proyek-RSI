<?php
session_start();
require_once('koneksi.php');

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil produk yang di-upload oleh user
$sql = "SELECT * FROM products WHERE penjual_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$product_result = $stmt->get_result();

// Menghapus produk
if (isset($_GET['delete'])) {
    $product_id = $_GET['delete'];
    $delete_sql = "DELETE FROM products WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $product_id);
    $delete_stmt->execute();

     $_SESSION['message'] = "Produk telah dihapus dengan sukses!"; 
    $_SESSION['message_type'] = "success"; // Jenis pesan (misalnya success, error, info)

    header("Location: manajemen_produk.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Produk</title>
    <!-- Menambahkan Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">

<header class="header">
        <div class="hamburger" onmouseover="showSidebar()" onmouseout="hideSidebar()">
            <i class="fas fa-bars"></i> <!-- Ikon garis tiga -->
        </div>
        <div class="logo">
            
        </div>
        <div class="header-actions">
            
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

<div class="container mx-auto mt-8 p-4">
    <h1 class="text-3xl font-bold text-center mb-6">Manajemen Produk</h1>
    
    <!-- Tampilkan pesan jika ada -->
    <?php
    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-' . $_SESSION['message_type'] . ' alert-dismissible fade show text-center mb-4" role="alert">
                ' . $_SESSION['message'] . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
        unset($_SESSION['message']);
    }
    ?>

    <!-- Grid Layout untuk Produk -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php while ($product = $product_result->fetch_assoc()): ?>
            <div class="product-card bg-white shadow-lg rounded-lg overflow-hidden">
                <img src="uploads/<?php echo $product['file_barang']; ?>" alt="Product Image" class="w-full h-56 object-cover">
                <div class="product-info p-4">
                    <h2 class="text-xl font-semibold mb-2"><?php echo $product['nama_barang']; ?></h2>
                    <p><strong>Harga:</strong> Rp <?php echo number_format($product['harga_barang'], 0, ',', '.'); ?></p>
                    <p><strong>Kategori:</strong> <?php echo $product['kategori_barang']; ?></p>
                    <p><strong>Status:</strong> <?php echo $product['status']; ?></p>
                </div>
                <div class="product-actions flex justify-between p-4 border-t">
                    <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">Edit</a>
                    <a href="manajemen_produk.php?delete=<?php echo $product['id']; ?>" class="btn bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Delete</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<footer class="footer"> 
        <p>&copy; 2024 Marketplace. Semua hak cipta dilindungi.</p>
    </footer>

<!-- Menambahkan Bootstrap JS -->
<script src="script.js"></script>
</body>
</html>

<?php
$conn->close();
?>
