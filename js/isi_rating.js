// Function to navigate to isi_review.html
function goToReviewPage() {
    window.location.href = "orders.php";
}

// Function to navigate back to tombol_pesanan.html
function goToOrderPage() {
    window.location.href = "orders.php";
}

// Function to handle star rating selection
function selectRating(rating) {
    const stars = document.querySelectorAll('.stars');
    stars.forEach((star, index) => {
        // Apply 'active' class to stars up to the selected rating
        star.classList.toggle('active', index < rating);
    });
}
function submitRating() {
    window.location.href = "ratingDONE.html";
  }

