function goToOrderPage() {
  window.location.href = "orders.php";
}

// Function to show notification when "Selesai" button is clicked
function submitReview() {
  window.location.href = "reviewDONE.html";
}

// Function to display the selected file name
// function displayFileName() {
//   const fileInput = document.getElementById("file-upload");
//   const fileName = document.getElementById("file-name");

//   if (fileInput.files.length > 0) {
//     fileName.textContent = fileInput.files[0].name;
//   }
// }

function displayFileName() {
  const fileInput = document.getElementById("file-upload");
  const fileName = document.getElementById("file-name");
  const imagePreview = document.getElementById("image-preview");

  if (fileInput.files.length > 0) {
    const file = fileInput.files[0];
    fileName.textContent = file.name;

    // Check if the file is an image
    if (file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = function(e) {
        imagePreview.src = e.target.result;
        imagePreview.style.display = "block"; // Show the image preview
      };
      reader.readAsDataURL(file);
    } else {
      imagePreview.style.display = "none"; // Hide if not an image
    }
  }
}


