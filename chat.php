<?php
// Koneksi ke database
$conn = new mysqli('localhost', 'root', '', 'user_register');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mengambil product_id dan seller_id dari URL
if (!isset($_GET['product_id']) || empty($_GET['product_id']) || !isset($_GET['seller_id']) || empty($_GET['seller_id'])) {
    die("Product ID atau Seller ID tidak ditemukan.");
}

$product_id = $_GET['product_id'];
$seller_id = $_GET['seller_id'];

// Mengambil data produk berdasarkan product_id
$sql_product = "SELECT * FROM products WHERE id = $product_id";
$product_result = $conn->query($sql_product);
if ($product_result->num_rows == 0) {
    die("Produk tidak ditemukan.");
}
$product = $product_result->fetch_assoc();

// Mengambil data penjual berdasarkan seller_id
$sql_seller = "SELECT * FROM users WHERE id = $seller_id";
$seller_result = $conn->query($sql_seller);
if ($seller_result->num_rows == 0) {
    die("Penjual tidak ditemukan.");
}
$seller = $seller_result->fetch_assoc();

// Mengambil riwayat percakapan untuk produk
$sql_messages = "SELECT * FROM negotiations WHERE product_id = $product_id ORDER BY created_at ASC";
$messages = $conn->query($sql_messages);

// Menangani pesan baru
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {
    $message = $_POST['message'];
    $user_id = 1; // ID pengguna yang sedang login, sesuaikan dengan session pengguna
    $sql_insert = "INSERT INTO negotiations (product_id, user_id, message) VALUES ($product_id, $user_id, '$message')";
    if ($conn->query($sql_insert) === TRUE) {
        header("Location: chat.php?product_id=$product_id&seller_id=$seller_id");
        exit;
    } else {
        echo "Terjadi kesalahan: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Negosiasi untuk Produk: <?php echo $product['nama_barang']; ?></title>
    <style>
        /* Styling untuk chat container */
        .chat-container {
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            max-width: 600px;
            margin: 20px auto;
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            height: 400px;
        }

        .message {
            display: flex;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 12px;
            max-width: 70%;
        }

        .message .user-name {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .message .timestamp {
            font-size: 0.8rem;
            color: #aaa;
            position: absolute;
            bottom: 5px;
            right: 5px;
        }

        .message-buyer {
            background-color: #f0f0f0;
            margin-left: auto;
            border-radius: 12px 12px 0 12px;
        }

        .message-seller {
            background-color: #3498db;
            color: white;
            border-radius: 12px 12px 12px 0;
        }

        .input-container {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
        }

        .input-container textarea {
            width: 80%;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #ccc;
            resize: none;
        }

        .input-container button {
            width: 18%;
            padding: 10px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }

        .input-container button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <h1>Negosiasi untuk Produk: <?php echo $product['nama_barang']; ?></h1>

    <div class="seller-info">
        <h3>Penjual: <?php echo $seller['Nama']; ?></h3>
        <img src="uploads/<?php echo $seller['profile_image']; ?>" alt="Profile Image" width="50">
    </div>

    <div class="chat-container">
        <?php
        // Menampilkan riwayat pesan
        if ($messages->num_rows > 0) {
            while ($row = $messages->fetch_assoc()) {
                // Mendapatkan informasi pengguna (penjual atau pembeli)
                $user_id = $row['user_id'];
                $user_sql = "SELECT * FROM users WHERE id = $user_id";
                $user_result = $conn->query($user_sql);
                $user = $user_result->fetch_assoc();

                // Menampilkan pesan dengan bubble chat
                $message_class = ($user_id == 1) ? 'message-seller' : 'message-buyer'; // Menentukan apakah ini penjual atau pembeli
                echo "<div class='message $message_class'>";
                echo "<div>";
                echo "<p class='user-name'>" . $user['Nama'] . "</p>";
                echo "<p>" . $row['message'] . "</p>";
                echo "<span class='timestamp'>" . $row['created_at'] . "</span>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>Tidak ada pesan.</p>";
        }
        ?>
    </div>

    <div class="input-container">
        <form action="chat.php?product_id=<?php echo $product_id; ?>&seller_id=<?php echo $seller_id; ?>" method="POST" style="width: 100%;">
            <textarea name="message" placeholder="Tulis pesan..." required></textarea>
            <button type="submit">Kirim</button>
        </form>
    </div>

</body>
</html>

<?php
$conn->close();
?>
