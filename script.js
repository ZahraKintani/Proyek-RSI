// Fungsi untuk menampilkan produk
function fetchProducts() {
    fetch('get-products.php')
        .then(response => response.json())
        .then(data => {
            const productContainer = document.getElementById('product-cards');
            productContainer.innerHTML = ''; // Kosongkan produk yang ada sebelumnya

            data.forEach(product => {
                const productCard = document.createElement('div');
                productCard.classList.add('product-card');

                productCard.innerHTML = `
                    <img src="${product.image_url}" alt="${product.name}">
                    <div class="product-name">${product.name}</div>
                    <div class="product-price">Rp ${product.price}</div>
                    <button class="negotiate-btn">Negosiasi</button>
                `;

                productContainer.appendChild(productCard);
            });
        })
        .catch(error => console.error('Error fetching products:', error));
}

// Memanggil fungsi fetchProducts saat halaman dimuat
window.onload = function() {
    fetchProducts();
};

// Fungsi untuk pencarian produk
function searchProduct() {
    const query = document.getElementById('search-input').value;
    fetch(`search-products.php?query=${query}`)
        .then(response => response.json())
        .then(data => {
            const productContainer = document.getElementById('product-cards');
            productContainer.innerHTML = ''; // Kosongkan produk yang ada sebelumnya

            data.forEach(product => {
                const productCard = document.createElement('div');
                productCard.classList.add('product-card');

                productCard.innerHTML = `
                    <img src="${product.image_url}" alt="${product.name}">
                    <div class="product-name">${product.name}</div>
                    <div class="product-price">Rp ${product.price}</div>
                    <button class="negotiate-btn">Negosiasi</button>
                `;

                productContainer.appendChild(productCard);
            });
        })
        .catch(error => console.error('Error searching products:', error));
}

// Fungsi untuk menampilkan/menyembunyikan sidebar dengan animasi
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const currentLeft = window.getComputedStyle(sidebar).left;

    // Toggle sidebar, periksa apakah sidebar sudah muncul atau tersembunyi
    if (currentLeft === '0px') {
        sidebar.style.left = '-250px';  // Menyembunyikan sidebar
    } else {
        sidebar.style.left = '0';  // Menampilkan sidebar
    }
}

// Menampilkan sidebar saat ikon hamburger di-hover
function showSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.style.left = '0'; // Menampilkan sidebar
}

// Menyembunyikan sidebar saat kursor keluar
function hideSidebar() {
    const sidebar = document.getElementById('sidebar');
    const sidebarHovered = sidebar.matches(':hover');

    // Menyembunyikan sidebar jika kursor tidak berada di sidebar
    if (!sidebarHovered) {
        sidebar.style.left = '-250px';  // Menyembunyikan sidebar
    }
}

// Event listener untuk pencarian produk saat mengetik (live search)
document.getElementById('search-input').addEventListener('input', searchProduct);



//bintang
let selectedRating = 0; // Variabel untuk menyimpan rating yang dipilih

function selectRating(rating) {
    selectedRating = rating; // Simpan rating yang dipilih
    const stars = document.querySelectorAll('.star');
    
    // Tandai bintang yang dipilih
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.add('selected'); // Tambahkan kelas untuk bintang yang dipilih
        } else {
            star.classList.remove('selected'); // Hapus kelas untuk bintang yang tidak dipilih
        }
    });
}

function goToReviewPage() {
    if (selectedRating === 0) {
        Swal.fire('Peringatan', 'Silakan pilih rating sebelum mengirim!', 'warning'); // Peringatan jika rating belum dipilih
        return;
    }
    window.location.href = `isi_review.html?product_id=${productId}&order_id=${orderId}&rating=${selectedRating}`;
}