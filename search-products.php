<?php
// Mencari produk berdasarkan query
$query = $_GET['query'];
$conn = new mysqli('localhost', 'root', '', 'user_register');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM products WHERE name LIKE '%$query%'";
$result = $conn->query($sql);

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

$conn->close();

echo json_encode($products);
?>