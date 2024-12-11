<?php
session_start();
require_once('koneksi.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['checkout'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $payment_method = $_POST['payment_method'];
    $user_id = $_SESSION['user_id'];

    // Simpan informasi transaksi ke database
    $sql = "INSERT INTO transaksi (user_id, product_id, product_name, price, payment_method, status) 
            VALUES (?, ?, ?, ?, ?, 'Pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisss", $user_id, $product_id, $product_name, $product_price, $payment_method);
    $stmt->execute();

    // Redirect ke halaman konfirmasi pembayaran
    header("Location: payment_confirmation.php?transaction_id=" . $stmt->insert_id);
    exit();
}
?>
