<?php
session_start();
require_once('koneksi.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['transaction_id'])) {
    $transaction_id = $_POST['transaction_id'];

    // Update status transaksi menjadi 'Completed'
    $sql = "UPDATE transaksi SET status = 'Completed' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $transaction_id);
    $stmt->execute();

    // Redirect ke halaman selesai pembayaran
    header("Location: payment_success.php?transaction_id=" . $transaction_id);
    exit();
}
?>
