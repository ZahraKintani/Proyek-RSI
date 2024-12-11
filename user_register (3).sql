-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 02 Des 2024 pada 09.13
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `user_register`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `chat`
--

CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `sender_name` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `conversations`
--

CREATE TABLE `conversations` (
  `id` int(11) NOT NULL,
  `user1_id` int(11) NOT NULL,
  `user2_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `harga_awal_user`
--

CREATE TABLE `harga_awal_user` (
  `produk_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `harga_awal` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `alamat` text NOT NULL,
  `phone` varchar(15) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `product_id` int(11) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `product_name` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `alamat`, `phone`, `payment_method`, `created_at`, `product_id`, `order_date`, `product_name`, `status`) VALUES
(1, 1, 70000.00, 'jl.sudi', '08123455566', 'transfer_bank', '2024-12-01 19:10:56', 0, '2024-12-02 04:36:15', NULL, NULL),
(2, 1, 140000.00, 'jl.Supratman', '081230777888', 'e_wallet', '2024-12-01 19:13:17', 0, '2024-12-02 04:36:15', NULL, NULL),
(3, 1, 131110.00, 'fhldsf', '3523', 'transfer_bank', '2024-12-01 19:14:26', 0, '2024-12-02 04:36:15', NULL, NULL),
(4, 1, 0.00, 'fhldsf', '3523', 'transfer_bank', '2024-12-01 19:14:29', 0, '2024-12-02 04:36:15', NULL, NULL),
(5, 1, 0.00, 'fhldsf', '3523', 'transfer_bank', '2024-12-01 19:14:34', 0, '2024-12-02 04:36:15', NULL, NULL),
(6, 1, 131110.00, 'Budpri', '081213013841', 'transfer_bank', '2024-12-01 19:21:13', 0, '2024-12-02 04:36:15', NULL, NULL),
(7, 1, 262220.00, 'dd', '3232', 'transfer_bank', '2024-12-01 19:39:16', 0, '2024-12-02 04:36:15', NULL, NULL),
(8, 1, 65555.00, 'Agi', '65464', 'bank_transfer', '2024-12-01 20:52:25', 0, '2024-12-02 04:36:15', NULL, NULL),
(9, 1, 70000.00, 'PP', '422424', 'bank_transfer', '2024-12-01 20:58:06', 0, '2024-12-02 04:36:15', NULL, NULL),
(10, 1, 65555.00, 'dd', '3232', 'credit_card', '2024-12-01 20:59:45', 0, '2024-12-02 04:36:15', NULL, NULL),
(11, 1, 0.00, 'PP', '081213013841', 'transfer_bank', '2024-12-01 21:16:46', 0, '2024-12-02 04:36:15', NULL, NULL),
(12, 1, 0.00, 'fsfsf', '2442', 'transfer_bank', '2024-12-01 21:17:17', 0, '2024-12-02 04:36:15', NULL, NULL),
(18, 1, 65555.00, 'sfds', '3232', 'Cash On Delivery', '2024-12-01 22:07:09', 0, '2024-12-02 05:07:09', NULL, 'pending');

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `penawaran`
--

CREATE TABLE `penawaran` (
  `penawaran_id` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `harga_tawaran` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL,
  `harga_awal_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `price_negotiations`
--

CREATE TABLE `price_negotiations` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `proposed_price` decimal(10,2) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `price_negotiations`
--

INSERT INTO `price_negotiations` (`id`, `product_id`, `proposed_price`, `user_id`, `created_at`) VALUES
(1, 3, 5000.00, 1, '2024-12-01 16:00:42'),
(2, 3, 20000.00, 1, '2024-12-01 16:12:03'),
(3, 3, 20000.00, 1, '2024-12-01 16:13:53'),
(4, 3, 20000.00, 1, '2024-12-01 16:14:31'),
(5, 6, 500000.00, 1, '2024-12-01 16:14:50'),
(6, 6, 500000.00, 1, '2024-12-01 16:17:00'),
(7, 7, 50000.00, 1, '2024-12-01 16:17:15'),
(8, 7, 50000.00, 1, '2024-12-01 16:18:49'),
(9, 7, 6999.00, 1, '2024-12-01 16:18:53'),
(10, 4, 5999.00, 1, '2024-12-01 16:28:17'),
(11, 4, 5999.00, 1, '2024-12-01 16:32:13'),
(12, 4, 5000.00, 1, '2024-12-01 16:32:17'),
(13, 4, 5000.00, 1, '2024-12-01 16:38:37'),
(14, 6, 5000.00, 1, '2024-12-01 17:06:11'),
(15, 3, 2666.00, 1, '2024-12-01 20:07:49'),
(16, 3, 2666.00, 1, '2024-12-01 20:07:52');

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `deskripsi_barang` text NOT NULL,
  `harga_barang` decimal(10,2) NOT NULL,
  `kategori_barang` varchar(100) NOT NULL,
  `file_barang` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `penjual_id` int(11) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'available',
  `latitude` decimal(9,6) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `user_id`, `nama_barang`, `deskripsi_barang`, `harga_barang`, `kategori_barang`, `file_barang`, `created_at`, `penjual_id`, `status`, `latitude`, `longitude`) VALUES
(1, 1, 'Kolor', 'asfasf', 4343534.00, 'Celana Dalam', 'product_1732700773.jpg', '2024-11-27 09:46:13', NULL, 'available', NULL, NULL),
(2, 1, 'Baju', 'Murah dan cepat beli', 80000.00, 'Atasan', 'product_1732701825.jpg', '2024-11-27 10:03:45', NULL, 'available', NULL, NULL),
(3, 0, 'Kolor', 'Kolor murah', 70000.00, 'Celana Dalam', 'product_1732702973.jpg', '2024-11-27 10:22:53', 2, 'available', NULL, NULL),
(4, 0, 'Baju', 'Baju Preloved', 65555.00, 'Atasan', 'product_1732703775.jpg', '2024-11-27 10:36:15', 2, 'available', NULL, NULL),
(9, 0, 'Test', 'Test', 5000.00, 'Test', 'product_1733075753.png', '2024-12-01 17:55:53', 1, 'available', NULL, NULL),
(10, 0, 'test', 'test', 75000.00, 'test', 'product_1733081161.jpg', '2024-12-01 19:26:01', 1, 'available', NULL, NULL),
(11, 0, 'test', 'test', 54000.00, 'test', 'product_1733081188.jpg', '2024-12-01 19:26:28', 1, 'available', NULL, NULL),
(12, 0, 'IPB', 'ipb', 1.00, 'Universitas', 'product_1733083181.png', '2024-12-01 19:59:41', 1, 'available', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `Nama` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `KataSandi` varchar(255) NOT NULL,
  `Alamat` text NOT NULL,
  `NomorTelepon` varchar(15) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `kabupaten_kota` varchar(100) DEFAULT NULL,
  `kecamatan` varchar(100) DEFAULT NULL,
  `RT` int(11) DEFAULT NULL,
  `RW` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `Nama`, `Email`, `KataSandi`, `Alamat`, `NomorTelepon`, `profile_image`, `provinsi`, `kabupaten_kota`, `kecamatan`, `RT`, `RW`) VALUES
(1, 'agi', 'agi@ub.ac.id', 'agi', 'Jln. Bahder Johan No. 12 A', '0823872385', 'profile_1.png', 'Sumatera Barat', 'Bukittinggi', 'MKS', 2347, 232),
(2, 'agi1', 'agi1@ub.ac.id', 'agites', 'dimana', '08124273813', 'profile_2.jpg', NULL, NULL, NULL, NULL, NULL),
(4, 'agi1', 'agi2@ub.ac.id', 'agi1', 'dimana', '08124273813d', 'profile_4.png', NULL, NULL, NULL, NULL, NULL),
(5, 'agi1', 'agi21@ub.ac.id', 'agi1', 'dimana', '0812427381334', NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'Agi', 'agi234@ub.ac.id', 'Agi', 'dimana', '0903253256', 'profile_6.jpg', NULL, NULL, NULL, NULL, NULL),
(7, '', '', 'admin', '', '', 'images (1).jpg', NULL, NULL, NULL, NULL, NULL),
(8, 'budpri', 'budpri@gmail.com', '12345', 'Jl.Sudirman', '086789067777', NULL, 'jawa timur', 'bojonegoro', NULL, NULL, NULL),
(9, 'niken', 'niken@gmail.com', '123456', 'jl.diponegoro', '0882009988877', NULL, 'jawa tengah', 'solo', NULL, 3, 12),
(10, 'n', 'niken1@gmail.com', '77777', 'jl.diponegoro', '088299999999', NULL, 'jawa tengah', 'semarang', NULL, 3, 12);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users_upload`
--

CREATE TABLE `users_upload` (
  `id_barang` int(11) NOT NULL,
  `Nama_Barang` varchar(100) NOT NULL,
  `Deskripsi_Barang` varchar(500) NOT NULL,
  `Kategori_Barang` varchar(100) NOT NULL,
  `Harga_Barang` decimal(10,2) NOT NULL,
  `File_Upload` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Indeks untuk tabel `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user1_id` (`user1_id`),
  ADD KEY `user2_id` (`user2_id`);

--
-- Indeks untuk tabel `harga_awal_user`
--
ALTER TABLE `harga_awal_user`
  ADD PRIMARY KEY (`produk_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversation_id` (`conversation_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `penawaran`
--
ALTER TABLE `penawaran`
  ADD PRIMARY KEY (`penawaran_id`),
  ADD KEY `produk_id` (`produk_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `harga_awal_user_id` (`harga_awal_user_id`);

--
-- Indeks untuk tabel `price_negotiations`
--
ALTER TABLE `price_negotiations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indeks untuk tabel `users_upload`
--
ALTER TABLE `users_upload`
  ADD PRIMARY KEY (`id_barang`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `penawaran`
--
ALTER TABLE `penawaran`
  MODIFY `penawaran_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `price_negotiations`
--
ALTER TABLE `price_negotiations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `users_upload`
--
ALTER TABLE `users_upload`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `chat_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_ibfk_1` FOREIGN KEY (`user1_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `conversations_ibfk_2` FOREIGN KEY (`user2_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `harga_awal_user`
--
ALTER TABLE `harga_awal_user`
  ADD CONSTRAINT `harga_awal_user_ibfk_1` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`produk_id`),
  ADD CONSTRAINT `harga_awal_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `penawaran`
--
ALTER TABLE `penawaran`
  ADD CONSTRAINT `penawaran_ibfk_1` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`produk_id`),
  ADD CONSTRAINT `penawaran_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `penawaran_ibfk_3` FOREIGN KEY (`harga_awal_user_id`) REFERENCES `harga_awal_user` (`produk_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
