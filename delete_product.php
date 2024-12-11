<?php
session_start();
require_once('koneksi.php');

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$product_id = $_GET['id'];

// Hapus produk dari database
$delete_sql = "DELETE FROM products WHERE id = ? AND penjual_id = ?";
$delete_stmt = $conn->prepare($delete_sql);
$delete_stmt->bind_param("ii", $product_id, $_SESSION['user_id']);
$delete_stmt->execute();

header("Location: profile.php");
exit();
?>
