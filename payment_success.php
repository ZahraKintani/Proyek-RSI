<?php
session_start();
require_once('koneksi.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$transaction_id = $_GET['transaction_id'];

// Ambil detail transaksi
$sql = "SELECT t.product_name, t.price, t.status, u.Nama AS user_name 
        FROM transaksi t 
        JOIN users u ON t.user_id = u.id 
        WHERE t.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $transaction_id);
$stmt->execute();
$result = $stmt->get_result();
$transaction = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sukses Pembayaran</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Pembayaran Berhasil</h1>
    <p>Terima kasih, <?php echo $transaction['user_name']; ?>!</p>
    <p>Produk: <?php echo $transaction['product_name']; ?></p>
    <p>Status Pembayaran: <?php echo $transaction['status']; ?></p>
    <p>Silakan tunggu konfirmasi dari penjual.</p>
</body>
</html>
