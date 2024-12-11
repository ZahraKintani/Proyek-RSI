<?php
// Koneksi ke database
$conn = new mysqli('localhost', 'root', '', 'user_register'); // Ganti dengan kredensial database Anda

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Menangani pengiriman pesan
if (isset($_POST['product_id']) && isset($_POST['message'])) {
    $product_id = $_POST['product_id'];
    $message = $_POST['message'];
    $sender_id = 1; // ID pengirim (misalnya dari session pengguna)
    $sender_name = "User"; // Nama pengirim, sesuaikan dengan data pengguna

    // Menyimpan pesan ke tabel chat
    $sql = "INSERT INTO chat (product_id, sender_id, sender_name, message) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiss", $product_id, $sender_id, $sender_name, $message);
    $stmt->execute();

    // Redirect kembali ke halaman chat untuk melihat pesan
    header("Location: chat.php?product_id=" . $product_id);
    exit;
}
?>
