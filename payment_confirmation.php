<?php
session_start();
require_once('koneksi.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$transaction_id = $_GET['transaction_id'];

// Ambil detail transaksi
$sql = "SELECT t.product_name, t.price, t.payment_method, t.status, u.Nama AS user_name 
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
    <title>Konfirmasi Pembayaran</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Konfirmasi Pembayaran</h1>
    <div class="payment-confirmation">
        <h2>Transaksi ID: <?php echo $transaction_id; ?></h2>
        <p>Nama Produk: <?php echo $transaction['product_name']; ?></p>
        <p>Harga: Rp <?php echo number_format($transaction['price'], 0, ',', '.'); ?></p>
        <p>Metode Pembayaran: <?php echo ucfirst(str_replace('_', ' ', $transaction['payment_method'])); ?></p>
        <p>Status Transaksi: <?php echo $transaction['status']; ?></p>
        
        <!-- Instruksi Pembayaran -->
        <h3>Instruksi Pembayaran:</h3>
        <p>Silakan melakukan pembayaran melalui <?php echo ucfirst($transaction['payment_method']); ?>. 
        Setelah pembayaran selesai, harap mengonfirmasi pembayaran di sini.</p>

        <form method="POST" action="process_payment.php">
            <input type="hidden" name="transaction_id" value="<?php echo $transaction_id; ?>">
            <button type="submit" class="btn">Konfirmasi Pembayaran</button>
        </form>
    </div>
</body>
</html>
