      // Menambahkan event listener untuk setiap tombol lokasi
      document.querySelectorAll('.location-button').forEach(button => {
        button.addEventListener('click', function() {
            this.classList.toggle('active');
        });
    });

    // Fungsi untuk mereset semua tombol
    function resetSelection() {
        document.querySelectorAll('.location-button').forEach(button => {
            button.classList.remove('active');
        });
    }

    //mengarahkan pindah
    document.querySelector('.pakai-button').addEventListener('click', function() {
        window.location.href = 'afterlokasi.html'; // Redirect to index.html
      });