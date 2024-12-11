<?php
$servername = "localhost"; // Nama server (biasanya localhost jika Anda menggunakan XAMPP atau MAMP)
$database = "user_register"; // Nama database Anda
$username = "root"; // Username MySQL, biasanya "root" jika di localhost
$password = ""; // Password MySQL, kosongkan jika di localhost (XAMPP biasanya tidak punya password)

$conn = mysqli_connect($servername, $username, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal : " . mysqli_connect_error());
} else {
    // Jika koneksi berhasil, Anda bisa menambahkan pesan atau log
    // echo "Koneksi berhasil"; 
}
?>
