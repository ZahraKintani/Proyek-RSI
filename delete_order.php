<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'user_register');

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    // Pastikan user yang menghapus adalah pemilik pesanan
    $user_id = $_SESSION['user_id'];

    // Query untuk memeriksa apakah pesanan milik user yang sedang login
    $sql_check = "SELECT id FROM orders WHERE id = ? AND user_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ii", $order_id, $user_id);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        // Query untuk menghapus order items terkait pesanan
        $sql_delete_items = "DELETE FROM order_items WHERE order_id = ?";
        $stmt_delete_items = $conn->prepare($sql_delete_items);
        $stmt_delete_items->bind_param("i", $order_id);
        $stmt_delete_items->execute();

        // Query untuk menghapus pesanan
        $sql_delete_order = "DELETE FROM orders WHERE id = ?";
        $stmt_delete_order = $conn->prepare($sql_delete_order);
        $stmt_delete_order->bind_param("i", $order_id);
        if ($stmt_delete_order->execute()) {
            echo "success"; // Mengirimkan respon sukses
        } else {
            echo "error"; // Mengirimkan respon gagal
        }
    } else {
        echo "not_found"; // Mengirimkan respon jika pesanan tidak ditemukan
    }
} else {
    echo "no_order_id"; // Tidak ada order_id yang dikirim
}

$conn->close();
?>
