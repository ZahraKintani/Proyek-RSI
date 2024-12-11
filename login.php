<?php
session_start();
require 'koneksi.php';

// Cek jika user sudah login, jika sudah, arahkan ke halaman home
if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

// Cek apakah form login sudah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Periksa apakah Email dan KataSandi ada di POST request
    if (isset($_POST["Email"]) && isset($_POST["KataSandi"])) {
        $Email = $_POST["Email"];
        $KataSandi = $_POST["KataSandi"];

        // Cek koneksi
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Menggunakan prepared statement untuk mencegah SQL Injection
        $query_sql = "SELECT * FROM users WHERE Email = ?";
        $stmt = $conn->prepare($query_sql);
        $stmt->bind_param("s", $Email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Cek jika user ditemukan
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Verifikasi password jika menggunakan plaintext
            if ($user['KataSandi'] == $KataSandi) {
                // Set sesi
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['Nama'];
                $_SESSION['user_email'] = $user['Email'];

                // Redirect ke halaman home.php
                header("Location: home.php");
                exit();
            } else {
                echo "Password salah!";
            }
        } else {
            echo "Email tidak ditemukan!";
        }

        $stmt->close();
    } else {
        echo "Silakan masukkan email dan kata sandi!";
    }
}

$conn->close();
?>
