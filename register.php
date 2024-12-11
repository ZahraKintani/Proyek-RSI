<?php
require 'koneksi.php';

// Mengambil data dari form
$Nama = isset($_POST["Nama"]) ? $_POST["Nama"] : null;
$Email = isset($_POST["Email"]) ? $_POST["Email"] : null;
$KataSandi = isset($_POST["KataSandi"]) ? $_POST["KataSandi"] : null;
$provinsi = isset($_POST["provinsi"]) ? $_POST["provinsi"] : null;
$kabupaten_kota = isset($_POST["kabupaten_kota"]) ? $_POST["kabupaten_kota"] : null;
$RT = isset($_POST["RT"]) ? $_POST["RT"] : null;
$RW = isset($_POST["RW"]) ? $_POST["RW"] : null;
$Alamat = isset($_POST["Alamat"]) ? $_POST["Alamat"] : null;
$NomorTelepon = isset($_POST["NomorTelepon"]) ? $_POST["NomorTelepon"] : null;

// Validasi apakah data kosong
if (
    empty($Nama) || empty($Email) || empty($KataSandi) || empty($provinsi) || 
    empty($kabupaten_kota) || empty($RT) || empty($RW) || 
    empty($Alamat) || empty($NomorTelepon)
) {
    die("Semua field harus diisi!");
}

// Cek apakah email sudah terdaftar
$query_check = "SELECT * FROM users WHERE Email = ?";
$stmt = $conn->prepare($query_check);
$stmt->bind_param("s", $Email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    header("Location: email-already-registered.html");
    exit;
}

// Masukkan data ke tabel users
$query_sql = "INSERT INTO users (Nama, Email, KataSandi, provinsi, kabupaten_kota, RT, RW, Alamat, NomorTelepon) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query_sql);
$stmt->bind_param(
    "sssssssss",
    $Nama,
    $Email,
    $KataSandi,
    $provinsi,
    $kabupaten_kota,
    $RT,
    $RW,
    $Alamat,
    $NomorTelepon
);

if ($stmt->execute()) {
    header("Location: success.html");  // Redirect ke halaman sukses jika berhasil
    exit;
} else {
    echo "Pendaftaran gagal: " . $stmt->error;
}
?>