<?php
session_start();
require 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data pengguna berdasarkan ID yang login
$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi password baru dan konfirmasi password
    if (empty($new_password) || empty($confirm_password)) {
        $error = "Password baru dan konfirmasi password tidak boleh kosong!";
    } elseif ($new_password !== $confirm_password) {
        $error = "Password baru dan konfirmasi password tidak cocok!";
    } else {
        // Hash password baru
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password di database
        $query = "UPDATE users SET KataSandi = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $hashed_password, $user_id);

        if ($stmt->execute()) {
            $success_message = "Password berhasil diubah.";
        } else {
            $error = "Terjadi kesalahan saat mengubah password. Silakan coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Marketplace</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="hamburger" onmouseover="showSidebar()" onmouseout="hideSidebar()">
            <i class="fas fa-bars"></i> <!-- Ikon garis tiga -->
        </div>
        <div class="logo">
            <h1>Marketplace</h1>
        </div>
        <div class="header-actions">
           
            <a href="upload.php" class="upload-btn">Upload Produk</a>
            <a href="chat.php" class="chat-btn"><i class="fas fa-comments"></i></a>
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

    <!-- Konten Reset Password -->
    <main class="main-content">
        <h2>Ubah Kata Sandi</h2>

        <?php if (isset($success_message)): ?>
            <p class="success"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="reset-password.php" method="POST" class="reset-password-form">
            <div class="form-group">
                <label for="new_password">Password Baru</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn-submit">Simpan Password Baru</button>
        </form>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 Marketplace. Semua hak cipta dilindungi.</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
