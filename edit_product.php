<?php
session_start();
require_once('koneksi.php');

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Cek apakah ID produk diberikan
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Ambil data produk dari database berdasarkan ID
    $sql = "SELECT * FROM products WHERE id = ? AND penjual_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $product_id, $user_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    // Cek jika produk ditemukan
    if (!$product) {
        echo "Produk tidak ditemukan atau Anda tidak memiliki akses untuk mengedit produk ini.";
        exit();
    }
} else {
    echo "ID produk tidak ditemukan.";
    exit();
}

// Update Produk
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['nama_barang'];
    $price = $_POST['harga_barang'];
    $category = $_POST['kategori_barang'];
    $status = $_POST['status']; // Misalnya untuk mengubah status produk

    // Cek apakah ada gambar yang di-upload
    if (isset($_FILES['file_barang']) && $_FILES['file_barang']['error'] == 0) {
        $image_tmp_name = $_FILES['file_barang']['tmp_name'];
        $image_name = $_FILES['file_barang']['name'];
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array(strtolower($image_extension), $allowed_extensions)) {
            $new_image_name = "product_" . $product_id . "." . $image_extension;
            $upload_path = 'uploads/' . $new_image_name;
            if (move_uploaded_file($image_tmp_name, $upload_path)) {
                // Update nama gambar di database jika berhasil
                $update_image_sql = "UPDATE products SET file_barang = ? WHERE id = ?";
                $update_image_stmt = $conn->prepare($update_image_sql);
                $update_image_stmt->bind_param("si", $new_image_name, $product_id);
                $update_image_stmt->execute();
            }
        }
    }

    // Update data produk di database
    $update_sql = "UPDATE products SET nama_barang = ?, harga_barang = ?, kategori_barang = ?, status = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssi", $name, $price, $category, $status, $product_id);
    $update_stmt->execute();

    // Redirect dengan query string untuk menampilkan notifikasi
    header("Location: edit_product.php?id=$product_id&success=true");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <link href="styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<header class="header">
    <div class="hamburger" onmouseover="showSidebar()" onmouseout="hideSidebar()">
        <i class="fas fa-bars"></i> 
    </div>
    <div class="logo">
        <h1>Marketplace</h1>
    </div>
    <div class="header-actions">
        
        <a href="upload.php" class="upload-btn">Upload Produk</a>
        <a href="chattemplate.html" class="chat-btn"><i class="fas fa-comments"></i></a>
    </div>
</header>

<!-- Sidebar -->
<nav class="sidebar" id="sidebar" onmouseover="showSidebar()" onmouseout="hideSidebar()">
    <ul>
        <li><a href="profile.php"><i class="fas fa-user"></i> Profil</a></li>
        <li><a href="home.php"><i class="fas fa-home"></i> Beranda</a></li>
        <li><a href="location.php"><i class="fas fa-map-marker-alt"></i> Range Lokasi Produk</a></li>
        <li><a href="LaporkanMasalah.html"><i class="fas fa-exclamation-circle"></i> Laporkan Masalah</a></li>
        <li><a href="orders.php"><i class="fas fa-box"></i> Pesanan</a></li>
    </ul>
</nav>

<div class="container my-5">
    <h2>Edit Produk</h2>

    <?php if (isset($_GET['success']) && $_GET['success'] == 'true'): ?>
        <div id="notification" class="alert alert-success alert-dismissible fade show" role="alert">
            Produk Anda berhasil diedit!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form method="POST" action="edit_product.php?id=<?php echo $product['id']; ?>" enctype="multipart/form-data" id="editProductForm">
        <div class="mb-4">
            <label for="nama_barang" class="form-label">Nama Produk:</label>
            <input type="text" id="nama_barang" name="nama_barang" class="form-control" value="<?php echo $product['nama_barang']; ?>" required>
        </div>

        <div class="mb-4">
            <label for="harga_barang" class="form-label">Harga:</label>
            <input type="number" id="harga_barang" name="harga_barang" class="form-control" value="<?php echo $product['harga_barang']; ?>" required>
        </div>

        <div class="mb-4">
            <label for="kategori_barang" class="form-label">Kategori:</label>
            <input type="text" id="kategori_barang" name="kategori_barang" class="form-control" value="<?php echo $product['kategori_barang']; ?>" required>
        </div>

        <div class="mb-4">
            <label for="status" class="form-label">Status:</label>
            <select id="status" name="status" class="form-select" required>
                <option value="tersedia" <?php echo ($product['status'] == 'tersedia') ? 'selected' : ''; ?>>Tersedia</option>
                <option value="terjual" <?php echo ($product['status'] == 'terjual') ? 'selected' : ''; ?>>Terjual</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="file_barang" class="form-label">Gambar Produk (opsional):</label>
            <?php if (!empty($product['file_barang'])): ?>
                <div class="mb-2">
                    <img src="uploads/<?php echo $product['file_barang']; ?>" alt="Gambar Produk" class="img-fluid" width="150">
                    <p>Gambar saat ini</p>
                </div>
            <?php endif; ?>
            <input type="file" name="file_barang" id="file_barang" accept="image/*" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary" id="submitButton">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" id="loadingSpinner" style="display: none;"></span>
            Simpan Perubahan
        </button>
    </form>
</div>

<footer class="footer">
        <p>&copy; 2024 Marketplace. Semua hak cipta dilindungi.</p>
    </footer>

<!-- Toastr notification library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="script.js"></script>
<script>
    document.getElementById('editProductForm').onsubmit = function() {
        document.getElementById('loadingSpinner').style.display = 'inline-block';
    };
</script>
</body>
</html>
