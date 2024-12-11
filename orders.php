<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'user_register');

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Pastikan pengguna login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Query untuk mengambil riwayat pesanan
$sql = "
    SELECT o.id AS order_id, o.created_at, o.total_amount, o.alamat, o.payment_method, 
           oi.product_id, p.nama_barang, oi.quantity
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    WHERE o.user_id = ? 
    ORDER BY o.created_at DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Tampilkan riwayat pesanan
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan</title>
    <link href="styles.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tambahkan link SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .order-card {
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            background-color: #fff;
            animation: fadeIn 0.5s ease-in-out;
        }
        .order-card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .order-header h4 {
            margin: 0;
        }
        .order-header button {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        .order-header button:hover {
            background-color: #c82333;
        }
        .order-info {
            margin-top: 20px;
        }
        .order-info p {
            margin: 5px 0;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
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


<div class="container">
    <h2 class="text-center mb-4">Riwayat Pesanan Anda</h2>

    <?php
    // Menampilkan riwayat pesanan jika ada
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $order_id = $row['order_id'];
            $created_at = $row['created_at'];
            $nama_barang = $row['nama_barang'];
            $total_amount = $row['total_amount'];
            
            $payment_method = $row['payment_method'];

            echo "
            <div class='order-card'>
                <div class='order-header'>
                    <h4>Pesanan #$order_id</h4>
                    <button class='delete-order' data-order-id='$order_id'><i class='fas fa-trash-alt'></i> Hapus Pesanan</button>
                </div>
                <div class='order-info'>
                    <p><strong>Produk:</strong> $nama_barang</p>
                    <p><strong>Total:</strong> " . number_format($total_amount, 0, ',', '.') . " IDR</p>
                    <p><strong>Metode Pembayaran:</strong> $payment_method</p>
                    <p><strong>Tanggal:</strong> $created_at</p>
                </div>
                <div class='order-actions'>
                    <a href='isi_rating.html' class='btn btn-primary'>Berikan Penilaian</a>
                    <a href='isi_review.html' class='btn btn-secondary'>Tulis Ulasan</a>
                </div>
            </div>";
        }
    } else {
        echo "<p class='text-center'>Anda belum memiliki pesanan.</p>";
    }
    ?>

</div>

<div class="notification"></div> <!-- Notifikasi Mengambang -->

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Tambahkan script SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

<script>
    $(document).ready(function() {
        // Hapus pesanan
        $('.delete-order').click(function() {
            var orderId = $(this).data('order-id');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Pesanan ini akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'delete_order.php', // Buat file PHP untuk menghapus pesanan
                        type: 'POST',
                        data: { order_id: orderId },
                        success: function(response) {
                            if (response == 'success') {
                                Swal.fire(
                                    'Terhapus!',
                                    'Pesanan telah berhasil dihapus.',
                                    'success'
                                );
                                location.reload(); // Refresh halaman setelah penghapusan
                            } else {
                                Swal.fire(
                                    'Gagal!',
                                    'Terjadi kesalahan saat menghapus pesanan.',
                                    'error'
                                );
                            }
                        }
                    });
                }
            });
        });
    });
</script>

<footer class="footer"> 
        <p>&copy; 2024 NPD. Semua hak cipta dilindungi.</p>
    </footer>

    <script src="script.js"></script>

</body>
</html>
