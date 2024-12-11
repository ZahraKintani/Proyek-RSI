<?php
session_start();
require_once('koneksi.php'); // Pastikan file konfigurasi koneksi database sudah ada

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil data profil dari database berdasarkan ID user yang login
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Update Profil
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];
    $provinsi = $_POST['provinsi'];
    $kabupaten_kota = $_POST['kabupaten_kota'];
    $kecamatan = $_POST['kecamatan'];
    $RT = $_POST['RT'];
    $RW = $_POST['RW'];

    // Update data pengguna di database
    $update_sql = "UPDATE users SET Nama = ?, Email = ?, Alamat = ?, NomorTelepon = ?, provinsi = ?, kabupaten_kota = ?, kecamatan = ?, RT = ?, RW = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssssssssi", $name, $email, $address, $phone_number, $provinsi, $kabupaten_kota, $kecamatan, $RT, $RW, $user_id);

    if ($update_stmt->execute()) {
        // Set notifikasi untuk sukses
        $_SESSION['notification'] = "Profil berhasil diperbarui!";
    } else {
        // Set notifikasi untuk error
        $_SESSION['notification'] = "Gagal memperbarui profil!";
    }

    header("Location: profile.php");
    exit(); // Pastikan untuk keluar setelah redirect
}

// Reset Password (gunakan hashing jika diinginkan)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Verifikasi password lama
    if ($current_password === $user['KataSandi']) {
        // Validasi password baru
        if ($new_password === $confirm_password) {
            // Update password di database tanpa hashing (jika tetap menggunakan plaintext)
            $update_password_sql = "UPDATE users SET KataSandi = ? WHERE id = ?";
            $update_password_stmt = $conn->prepare($update_password_sql);
            $update_password_stmt->bind_param("si", $new_password, $user_id);

            if ($update_password_stmt->execute()) {
                $_SESSION['notification'] = "Password berhasil direset!";
            } else {
                $_SESSION['notification'] = "Gagal mereset password!";
            }
        } else {
            $_SESSION['notification'] = "Password baru tidak cocok.";
        }
    } else {
        $_SESSION['notification'] = "Password lama salah.";
    }

    header("Location: profile.php");
    exit(); // Pastikan untuk keluar setelah redirect
}

// Hapus Akun
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_account'])) {
    // Hapus akun dari database
    // Hapus semua pesanan terkait pengguna
    $delete_orders_sql = "DELETE FROM orders WHERE user_id = ?";
    $delete_orders_stmt = $conn->prepare($delete_orders_sql);
    $delete_orders_stmt->bind_param("i", $user_id);
    $delete_orders_stmt->execute();

    // Hapus akun dari database
    $delete_sql = "DELETE FROM users WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $user_id);
    if ($delete_stmt->execute()) {
        // Hapus session dan redirect ke halaman login
        session_destroy();
        header("Location: index.html");
        exit();
    } else {
        echo "Gagal menghapus akun.";
    }
}

// Di halaman profile.php, Anda bisa menampilkan notifikasi jika ada
if (isset($_GET['notification'])) {
    echo "<div class='notification'>" . htmlspecialchars($_GET['notification']) . "</div>";
}

// Meng-upload gambar profil
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_image'])) {
    // Cek apakah file gambar ada
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $image_tmp_name = $_FILES['profile_image']['tmp_name'];
        $image_name = $_FILES['profile_image']['name'];
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);

        // Validasi ekstensi gambar
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($image_extension), $allowed_extensions)) {
            // Tentukan path untuk menyimpan gambar
            $new_image_name = "profile_" . $user_id . "." . $image_extension;
            $upload_path = 'uploads/' . $new_image_name;

            // Pindahkan file gambar ke direktori uploads
            if (move_uploaded_file($image_tmp_name, $upload_path)) {
                // Update nama gambar profil di database
                $update_image_sql = "UPDATE users SET profile_image = ? WHERE id = ?";
                $update_image_stmt = $conn->prepare($update_image_sql);
                $update_image_stmt->bind_param("si", $new_image_name, $user_id);
                $update_image_stmt->execute();

                echo '<script>
                Swal.fire({
                    title: "Sukses!",
                    text: "Gambar profil berhasil di-upload.",
                    icon: "success",
                    confirmButtonText: "Tutup"
                });
            </script>';

                // Refresh halaman setelah upload
                header("Location: profile.php");
            } else {
                echo "Gagal meng-upload gambar.";
            }
        } else {
            echo "Ekstensi file tidak valid. Harus jpg, jpeg, png, atau gif.";
        }
    } else {
        echo "Tidak ada file yang dipilih atau terjadi kesalahan saat meng-upload gambar.";
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* Header Animations */
        /* Tombol Hover */
        .upload-btn,
        .product-management-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s ease;
        }

        .logout-btn {
            background-color: red;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s ease;
        }

        .upload-btn:hover,
        .product-management-btn:hover,
        .logout-btn:hover {
            background-color: #45a049;
            transform: translateY(-3px);
        }

        .hapus-akun-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s ease;
        }
        .hapus-akun-btn:hover {
        background-color: #45a049;
            transform: translateY(-3px);
        }

    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="hamburger" onmouseover="showSidebar()" onmouseout="hideSidebar()">
            <i class="fas fa-bars"></i> <!-- Ikon garis tiga -->
        </div>
        <div class="logo"></div>
        <div class="header-actions">
            <a href="upload.php" class="upload-btn">Upload Produk</a>
            <a href="chattemplate.html" class="chat-btn"><i class="fas fa-comments"></i></a>
            <a href="manajemen_produk.php" class="product-management-btn">Manajemen Produk</a> <!-- Tombol ke halaman manajemen produk -->
            <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
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

    <!-- Main Content -->
    <div class="main-content">
        <h1>Profil Pengguna</h1>
        <div class="profile-container">
            <!-- Gambar Profil -->
            <div class="profile-image-container">
                <div class="profile-image">
                    <img src="uploads/<?php echo $user['profile_image']; ?>" alt="Profile Image" id="profile-img">
                </div>
                <form method="POST" action="profile.php" enctype="multipart/form-data" class="upload-form">
                    <input type="file" name="profile_image" id="profile_image" accept="image/*" required>
                    <button type="submit" name="upload_image">Upload</button>
                </form>
            </div>

            <!-- Form Update Profil -->
            <div class="profile-form-container">
                <form method="POST" action="profile.php">
                    <h2>Update Profil</h2>
                    <label for="name">Nama Lengkap:</label>
                    <input type="text" id="name" name="name" value="<?php echo $user['Nama']; ?>" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo $user['Email']; ?>" required>
                    <label for="phone_number">Nomor Telepon:</label>
                    <input type="text" id="phone_number" name="phone_number" value="<?php echo $user['NomorTelepon']; ?>" required>

                    <!-- New Fields -->
                    <label for="provinsi">Provinsi:</label>
                    <input type="text" id="provinsi" name="provinsi" value="<?php echo $user['provinsi']; ?>" required>

                    <label for="kabupaten_kota">Kabupaten/Kota:</label>
                    <input type="text" id="kabupaten_kota" name="kabupaten_kota" value="<?php echo $user['kabupaten_kota']; ?>" required>

                    <label for="kecamatan">Kecamatan:</label>
                    <input type="text" id="kecamatan" name="kecamatan" value="<?php echo $user['kecamatan']; ?>" required>

                    <label for="RT">RT:</label>
                    <input type="text" id="RT" name="RT" value="<?php echo $user['RT']; ?>" required>

                    <label for="RW">RW:</label>
                    <input type="text" id="RW" name="RW" value="<?php echo $user['RW']; ?>" required>

                    <label for="address">Alamat Lengkap:</label>
                    <textarea id="address" name="address" required><?php echo $user['Alamat']; ?></textarea>


                    <button type="submit" name="update_profile">Update Profil</button>
                </form>
         
                <form method="POST" action="profile.php">
                    <h2>Reset Password</h2>
                    <label for="current_password">Password Lama:</label>
                    <input type="password" id="current_password" name="current_password" required>

                    <label for="new_password">Password Baru:</label>
                    <input type="password" id="new_password" name="new_password" required>

                    <label for="confirm_password">Konfirmasi Password Baru:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>

                    <button type="submit" name="reset_password">Reset Password</button>

                </form>

                <!-- Form Hapus Akun -->
                 <form method="POST" action="profile.php" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?');">
                    <h2>Hapus Akun</h2>
                    <button type="submit" name="delete_account" class="hapus-akun-btn">Hapus</button>
                </form>
            </div>
        </div>
    </div>
            </div>
        </div>
    </div>

<!-- Tambahkan SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<script>
    <?php if (isset($_SESSION['notification'])): ?>
        Swal.fire({
            title: "Notifikasi",
            text: "<?php echo $_SESSION['notification']; ?>",
            icon: "success",
            timer: 3000,
            showConfirmButton: false
        });
        <?php unset($_SESSION['notification']); ?>
    <?php endif; ?>
</script>
</body>
</html>

    <footer class="footer"> 
        <p>&copy; 2024 Marketplace. Semua hak cipta dilindungi.</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
