// Filter kategori di halaman koleksi
// Tombol .chip punya data-filter, kartu .produk-card punya data-kategori.
// Nilainya: semua | kebaya | heels | kemben | aksesoris
document.addEventListener('DOMContentLoaded', function () {
  var chips = document.querySelectorAll('.chip[data-filter]');
  var cards = document.querySelectorAll('.produk-card[data-kategori]');
  var countLabel = document.getElementById('katalog-count');
  var judul = document.getElementById('katalog-title');
  var emptyText = document.getElementById('produk-empty');

  // Di database kategorinya masih bernama "Kemben" (slug "kemben"), sedangkan kartu memakai "kamisol"
  function samakan(kategori) {
    return kategori.replace('kemben', 'kamisol');
  }

  function terapkanFilter(kategori, kataKunci) {
    var jumlah = 0;
    var cari = (typeof kataKunci === 'string' ? kataKunci : (new URLSearchParams(window.location.search).get('q') || '')).trim().toLowerCase();

    cards.forEach(function (card) {
      // Chip memakai slug dari database (mis. "pakaian-kebaya"), kartu memakai kata kunci ("kebaya")
      var kunci = samakan(kategori);
      var cocokKategori = kunci === 'semua' ||
        card.dataset.kategori === kunci ||
        kunci.indexOf(card.dataset.kategori) !== -1;

      var nama = (card.querySelector('.produk-name') ? card.querySelector('.produk-name').textContent : '').toLowerCase();
      var ket = (card.querySelector('.produk-size') ? card.querySelector('.produk-size').textContent : '').toLowerCase();
      var cocokCari = !cari || nama.indexOf(cari) !== -1 || ket.indexOf(cari) !== -1;

      var cocok = cocokKategori && cocokCari;
      card.classList.toggle('is-hidden', !cocok);
      if (cocok) jumlah++;
    });

    chips.forEach(function (chip) {
      chip.classList.toggle('active', chip.dataset.filter === kategori);
    });

    // Judul ikut kategori: "Semua Koleksi", "Semua Koleksi Kebaya", "Semua Koleksi Heels", ...
    if (judul) {
      var aktif = document.querySelector('.chip.active');
      var nama = aktif && kategori !== 'semua' ? aktif.textContent.replace(/^(Pakaian|Paket)\s+/i, '').trim() : '';
      if (cari) {
        judul.textContent = 'Hasil pencarian "' + cari + '"';
      } else {
        judul.textContent = nama ? 'Semua Koleksi ' + nama : 'Semua Koleksi';
      }
    }

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

  // Klik di mana saja pada kotak produk: simpan data produk untuk halaman detail (js/detail.js), lalu buka detail
  document.querySelectorAll('.produk-card').forEach(function (card) {
    // Alamat detail: dari tautan /detail di dalam kotak (foto atau tombol Sewa); jika tidak ada, dibuat dari data-id
    var tautan = card.querySelector('a[href*="/detail"]');
    var alamatDetail = tautan ? tautan.href : window.location.origin + '/detail?id=' + encodeURIComponent(card.dataset.id);

    function simpan() {
      var produk = {
        id: card.dataset.id,
        nama: card.querySelector('.produk-name').textContent.trim(),
        kategori: card.querySelector('.produk-badge').textContent.trim(),
        keterangan: card.querySelector('.produk-size').textContent.trim(),
        harga: CabilCart.keAngka(card.querySelector('.produk-price').textContent),
        gambar: card.querySelector('.produk-image img').getAttribute('src'),
        durasi: 3
      };
      try {
        localStorage.setItem('cabilrent-produk-dipilih', JSON.stringify(produk));
      } catch (e) {
        // Penyimpanan diblokir browser: detail memakai data bawaan
      }
    }

    card.addEventListener('click', function (event) {
      simpan();
      // Klik pada tautan foto sudah membuka detail sendiri; klik di bagian lain kotak diarahkan ke sana
      if (!event.target.closest('a')) {
        window.location.href = alamatDetail;
      }
    });
  });

  // Saat halaman dibuka dengan ?kategori=... (misal dari footer atau home)
  var awal = new URLSearchParams(window.location.search).get('kategori');
  var valid = Array.prototype.some.call(chips, function (chip) {
    return chip.dataset.filter === awal;
  });
  terapkanFilter(valid ? awal : 'semua');

  // Pencarian lewat input navbar di halaman koleksi
  var inputCari = document.querySelector('.nav-search input[name="q"]');
  if (inputCari) {
    inputCari.addEventListener('input', function () {
      var aktif = document.querySelector('.chip.active');
      terapkanFilter(aktif ? aktif.dataset.filter : 'semua', inputCari.value);
    });
  }

  // Scroll langsung ke bagian katalog jika ada filter atau pencarian
  var params = new URLSearchParams(window.location.search);
  if (params.has('q') || (params.has('kategori') && params.get('kategori') !== 'semua') || window.location.hash === '#semua-koleksi') {
    var sectionKoleksi = document.getElementById('semua-koleksi');
    if (sectionKoleksi) {
      setTimeout(function () {
        sectionKoleksi.scrollIntoView({ behavior: 'smooth' });
      }, 150);
    }
  }
});
