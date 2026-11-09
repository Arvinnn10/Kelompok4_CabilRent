// Filter kategori di halaman koleksi
// Tombol .chip punya data-filter, kartu .produk-card punya data-kategori.
// Nilainya: semua | kebaya | heels | kemben | aksesoris
document.addEventListener('DOMContentLoaded', function () {
  var chips = document.querySelectorAll('.chip[data-filter]');
  var cards = document.querySelectorAll('.produk-card[data-kategori]');
  var countLabel = document.getElementById('katalog-count');
  var emptyText = document.getElementById('produk-empty');

  function terapkanFilter(kategori) {
    var jumlah = 0;

    cards.forEach(function (card) {
      var cocok = kategori === 'semua' || card.dataset.kategori === kategori;
      card.classList.toggle('is-hidden', !cocok);
      if (cocok) jumlah++;
    });

    chips.forEach(function (chip) {
      chip.classList.toggle('active', chip.dataset.filter === kategori);
    });

    if (countLabel) countLabel.textContent = jumlah + ' produk';
    if (emptyText) emptyText.hidden = jumlah > 0;

    // Simpan pilihan di URL (?kategori=heels) tanpa memuat ulang halaman
    var url = new URL(window.location.href);
    if (kategori === 'semua') {
      url.searchParams.delete('kategori');
    } else {
      url.searchParams.set('kategori', kategori);
    }
    window.history.replaceState(null, '', url);
  }

  chips.forEach(function (chip) {
    chip.addEventListener('click', function (event) {
      event.preventDefault();
      terapkanFilter(chip.dataset.filter);
    });
  });

  // Kartu kategori di atas (Pakaian Kebaya, Heels, Kemben) ikut memfilter
  document.querySelectorAll('.kategori-card').forEach(function (link) {
    link.addEventListener('click', function (event) {
      var kategori = new URL(link.href).searchParams.get('kategori');
      if (!kategori) return;
      event.preventDefault();
      terapkanFilter(kategori);
      document.querySelector('.katalog-section').scrollIntoView({ behavior: 'smooth' });
    });
  });

  // Foto produk yang belum ada disembunyikan, jadi tampil kotak coklat polos
  document.querySelectorAll('.produk-image img').forEach(function (img) {
    function tandaiKosong() {
      img.classList.add('is-missing');
    }
    if (img.complete && img.naturalWidth === 0) tandaiKosong();
    img.addEventListener('error', tandaiKosong);
  });

  // Saat halaman dibuka dengan ?kategori=... (misal dari footer atau home)
  var awal = new URLSearchParams(window.location.search).get('kategori');
  var valid = Array.prototype.some.call(chips, function (chip) {
    return chip.dataset.filter === awal;
  });
  terapkanFilter(valid ? awal : 'semua');
});
