<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter Produk Berdasarkan Lokasi</title>
    <!-- Link ke Google Fonts untuk font modern -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #e2e2e2, #fff);
            overflow-x: hidden;
            color: #333;
            padding: 0;
            margin: 0;
        }

        .container {
            width: 80%;
            max-width: 1200px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #2c8576;
            font-size: 36px;
            margin-bottom: 40px;
            animation: fadeIn 1s ease-out;
        }

        /* Form Layout */
        form {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
            animation: fadeIn 2s ease-out;
        }

        .form-group {
            flex: 1;
            min-width: 250px;
        }

        select, button {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            transition: all 0.3s ease;
        }

        select:focus, button:focus {
            outline: none;
            border-color: #2c8576;
        }

        select:hover, button:hover {
            background-color: #2c8576;
            color: white;
            transform: translateY(-2px);
        }

        button {
            background-color: #2c8576;
            color: white;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, transform 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
            transform: translateY(-3px);
        }

        /* Product Grid */
        .product {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-top: 40px;
            animation: fadeIn 2.5s ease-out;
        }

        .product-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .product-card img:hover {
            transform: scale(1.1);
        }

        .product-card h3 {
            font-size: 22px;
            color: #333;
            margin: 15px 0;
            transition: color 0.3s ease;
        }

        .product-card p {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .product-card .price {
            font-size: 18px;
            font-weight: bold;
            color: #2c8576;
        }

        .product-card .location {
            font-size: 14px;
            color: #888;
            margin-top: 10px;
        }

        .product-card button {
            width: 100%;
            padding: 12px;
            background-color: #2c8576;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .product-card button:hover {
            background-color: #45a049;
        }

        /* Media Queries for Mobile Responsiveness */
        @media screen and (max-width: 1024px) {
            .product {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media screen and (max-width: 768px) {
            .product {
                grid-template-columns: 1fr;
            }

            form {
                flex-direction: column;
            }

            select, button {
                width: 100%;
                margin-bottom: 15px;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes bounce {
            0% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
            100% {
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
<header class="header">
        <div class="hamburger" onmouseover="showSidebar()" onmouseout="hideSidebar()">
            <i class="fas fa-bars"></i> <!-- Ikon garis tiga -->
        </div>
        <div class="logo">
            
        </div>
        
            <a href="chattemplate.html" class="chat-btn"><i class="fas fa-comments"></i></a>
        </div>
    </header>

    <nav class="sidebar" id="sidebar" onmouseover="showSidebar()" onmouseout="hideSidebar()">
        <ul>
            <li><a href="profile.php"><i class="fas fa-user"></i> Profil</a></li>
            <li><a href="home.php"><i class="fas fa-home"></i> Beranda</a></li>
            <li><a href="location.php"><i class="fas fa-map-marker-alt"></i> Range Lokasi Produk</a></li>
            <li><a href="LaporkanMasalah.html"><i class="fas fa-exclamation-circle"></i> Laporkan Masalah</a></li>
            <li><a href="orders.php"><i class="fas fa-box"></i> Pesanan</a></li>
        </ul>
    </nav>



    <div class="container">
        <h1>Filter Produk Berdasarkan Lokasi</h1>

        <!-- Form Filter Lokasi -->
        <form>
            <div class="form-group">
                <label for="provinsi">Provinsi:</label>
                <select name="provinsi" id="provinsi">
                    <option value="">Pilih Provinsi</option>
                    <option value="Jakarta">Jakarta</option>
                    <option value="Bali">Bali</option>
                    <option value="Jawa Timur">Jawa Timur</option>
                    <option value="Sumatra Utara">Sumatra Utara</option>
                </select>
            </div>

            <div class="form-group">
                <label for="kabupaten_kota">Kabupaten/Kota:</label>
                <select name="kabupaten_kota" id="kabupaten_kota">
                    <option value="">Pilih Kabupaten/Kota</option>
                    <!-- Opsi kabupaten/kota akan muncul berdasarkan provinsi yang dipilih -->
                </select>
            </div>

            <button type="submit">Filter Produk</button>
        </form>

        <hr>

        <!-- Menampilkan Produk Berdasarkan Lokasi yang Dipilih -->
        <div class="product">
            <div class="product-card">
                <img src="https://via.placeholder.com/400x200" alt="Produk 1">
                <h3>Produk 1</h3>
                <p>Deskripsi produk 1. Produk unggulan dengan kualitas terbaik.</p>
                <p class="price">Rp. 100.000</p>
                <p class="location">Lokasi: Jakarta, Jakarta Pusat</p>
                <button>Lihat Detail</button>
            </div>

            <div class="product-card">
                <img src="https://via.placeholder.com/400x200" alt="Produk 2">
                <h3>Produk 2</h3>
                <p>Deskripsi produk 2. Tersedia dalam beberapa varian warna.</p>
                <p class="price">Rp. 250.000</p>
                <p class="location">Lokasi: Bali, Denpasar</p>
                <button>Lihat Detail</button>
            </div>

            <div class="product-card">
                <img src="https://via.placeholder.com/400x200" alt="Produk 3">
                <h3>Produk 3</h3>
                <p>Deskripsi produk 3. Bergaransi resmi dan sangat populer di kalangan pembeli.</p>
                <p class="price">Rp. 150.000</p>
                <p class="location">Lokasi: Jawa Timur, Surabaya</p>
                <button>Lihat Detail</button>
            </div>
        </div>
    </div>

    <!-- JavaScript untuk Mengubah Dropdown Kabupaten/Kota -->
    <script>
        const provinsiSelect = document.getElementById('provinsi');
        const kabupatenSelect = document.getElementById('kabupaten_kota');

        const kabupatenData = {
            "Jakarta": ["Jakarta Barat", "Jakarta Pusat", "Jakarta Selatan", "Jakarta Timur"],
            "Bali": ["Denpasar", "Badung", "Gianyar"],
            "Jawa Timur": ["Surabaya", "Malang", "Kediri"],
            "Sumatra Utara": ["Medan", "Binjai", "Pematangsiantar"]
        };

        provinsiSelect.addEventListener('change', function() {
            const selectedProvinsi = provinsiSelect.value;
            kabupatenSelect.innerHTML = "<option value=''>Pilih Kabupaten/Kota</option>"; // Reset kabupaten options
            if (selectedProvinsi) {
                kabupatenData[selectedProvinsi].forEach(function(kabupaten) {
                    const option = document.createElement('option');
                    option.value = kabupaten;
                    option.textContent = kabupaten;
                    kabupatenSelect.appendChild(option);
                });
            }
        });
    </script>
<footer class="footer"> 
        <p>&copy; 2024 Marketplace. Semua hak cipta dilindungi.</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
