<?php
// Memulai session dan koneksi ke database
session_start();
require_once('koneksi.php');

// Cek apakah user sudah login, jika tidak, arahkan ke halaman login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Proses upload produk setelah form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $nama_barang = $_POST['Nama_Barang'];
    $deskripsi_barang = $_POST['Deskripsi_Barang'];
    $harga_barang = $_POST['Harga_Barang'];
    $kategori_barang = $_POST['Kategori_Barang'];
    $penjual_id = $_SESSION['user_id'];  // ID user yang sedang login
    
    // Menangani file upload
    if (isset($_FILES['File_Upload']) && $_FILES['File_Upload']['error'] == 0) {
        $file_tmp_name = $_FILES['File_Upload']['tmp_name'];
        $file_name = $_FILES['File_Upload']['name'];
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf']; // Format yang diterima

        // Memeriksa apakah file memiliki ekstensi yang diperbolehkan
        if (in_array(strtolower($file_extension), $allowed_extensions)) {
            // Mengubah nama file menjadi unik
            $new_file_name = "product_" . time() . "." . $file_extension;
            $upload_path = 'uploads/' . $new_file_name;

            // Upload file ke server
            if (move_uploaded_file($file_tmp_name, $upload_path)) {
                // Menyimpan data produk ke database
                $sql = "INSERT INTO products (penjual_id, nama_barang, deskripsi_barang, harga_barang, kategori_barang, file_barang)
                        VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("issdss", $penjual_id, $nama_barang, $deskripsi_barang, $harga_barang, $kategori_barang, $new_file_name);
                
                if ($stmt->execute()) {
                    // Set session untuk feedback sukses
                    $_SESSION['upload_success'] = "Produk berhasil diunggah!";
                    header("Location: home.php"); // Arahkan ke halaman home setelah upload berhasil
                    exit();
                } else {
                    echo "Gagal meng-upload produk.";
                }
            } else {
                echo "Gagal meng-upload file.";
            }
        } else {
            echo "Format file tidak didukung. Hanya JPG, PNG, dan PDF yang diperbolehkan.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Barang</title>
    <link rel="stylesheet" href="css/uploadbarang.css">  <!-- Menghubungkan file CSS -->
</head>
<body>
    <div class="header-1"></div>

    <div class="background">
        <div class="text">
            <h1>Upload Barang</h1>
            <h2>Silakan unggah barang yang akan Anda jual</h2>
            <h3>Format file: png, jpg, dan pdf</h3>
        </div>
        
        <!-- Form Upload Barang -->
        <div class="form-container">
            <!-- Tempat pratinjau file -->
            <div id="preview-container"></div>

            <!-- Form input barang -->
            <form action="upload.php" method="POST" enctype="multipart/form-data">
                <label for="upfoto" class="upload-box"></label>
                <input type="file" id="upfoto" name="File_Upload" accept=".png, .jpg, .jpeg, .pdf" style="display: none;" required>

                <!-- Input data barang -->
                <div class="isiform">
                    <label for="nama">Nama Barang:</label>
                    <input type="text" id="nama" name="Nama_Barang" required>
                </div>
                <div class="isiform">
                    <label for="deskripsi">Deskripsi Barang:</label>
                    <textarea id="deskripsi" name="Deskripsi_Barang" rows="4" required></textarea>
                </div>
                <div class="isiform">
                    <label for="harga">Harga Barang:</label>
                    <input type="number" id="harga" name="Harga_Barang" required>
                </div>
                <div class="isiform">
                    <label for="kategori">Kategori Barang:</label>
                    <input type="text" id="kategori" name="Kategori_Barang" required>
                </div>
                
                <!-- Tombol Kirim -->
                <button type="submit" class="tombolkirim">Kirim</button>
            </form>
        </div>
        <!-- Tombol kembali -->
        <button class="tombolback" onclick="window.location.href='profile.php'"></button>
    </div>

    <script>
        // Fungsi untuk pratinjau file gambar atau PDF yang di-upload
        document.getElementById('upfoto').addEventListener('change', function () {
            const file = this.files[0];
            const previewContainer = document.getElementById('preview-container');
            previewContainer.innerHTML = "";

            if (!file) return;

            const allowedExtensions = ['image/png', 'image/jpeg', 'application/pdf'];
            if (allowedExtensions.includes(file.type)) {
                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.style.maxWidth = '150px';
                    img.style.border = '1px solid #ccc';
                    img.style.borderRadius = '10px';
                    previewContainer.appendChild(img);
                } else if (file.type === 'application/pdf') {
                    const iframe = document.createElement('iframe');
                    iframe.src = URL.createObjectURL(file);
                    iframe.style.width = '150px';
                    iframe.style.height = '200px';
                    previewContainer.appendChild(iframe);
                }
            } else {
                alert("Format file tidak didukung. Hanya PNG, JPG, dan PDF yang diperbolehkan.");
                this.value = ""; // Reset input file
            }
        });
    </script>
</body>
</html>
