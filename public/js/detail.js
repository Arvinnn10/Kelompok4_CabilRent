// Halaman detail produk.
// Produk yang diklik di halaman koleksi disimpan sementara di localStorage
// ('cabilrent-produk-dipilih'), lalu ditampilkan di sini. Kalau tidak ada,
// halaman memakai data bawaan di atribut data-* pada #detail-produk.
document.addEventListener('DOMContentLoaded', function () {
  var root = document.getElementById('detail-produk');
  var form = document.getElementById('detail-form');
  var btnTambah = document.getElementById('btn-tambah-keranjang');
  var btnTanya = document.getElementById('btn-tanya');
  var gambar = document.getElementById('detail-gambar');

  var produk = {
    id: root.dataset.id,
    nama: root.dataset.nama,
    kategori: root.dataset.kategori,
    keterangan: root.dataset.keterangan,
    harga: parseInt(root.dataset.harga, 10),
    gambar: root.dataset.gambar,
    durasi: 3
  };

  // Ganti dengan produk yang dipilih di halaman koleksi (?id=...)
  var idDiUrl = new URLSearchParams(window.location.search).get('id');
  var dipilih = null;
  try {
    dipilih = JSON.parse(localStorage.getItem('cabilrent-produk-dipilih'));
  } catch (e) {
    dipilih = null;
  }
  if (dipilih && dipilih.id === idDiUrl) {
    produk = dipilih;
  }

  // Isi tampilan
  document.getElementById('detail-kategori').textContent = produk.kategori;
  document.getElementById('detail-nama').textContent = produk.nama;
  document.getElementById('detail-harga').textContent = CabilCart.keRupiah(produk.harga);
  document.getElementById('detail-keterangan').textContent = produk.keterangan;
  document.title = produk.nama + ' - Cabil.Rent';
  gambar.src = produk.gambar;
  gambar.alt = produk.nama;

  // Data sudah terisi: tampilkan (sebelumnya disembunyikan agar produk bawaan tidak sempat terlihat)
  root.style.visibility = 'visible';

  // Foto yang belum ada tampil kotak coklat
  function tandaiKosong() {
    gambar.classList.add('is-missing');
  }
  if (gambar.complete && gambar.naturalWidth === 0) tandaiKosong();
  gambar.addEventListener('error', tandaiKosong);

  // Tombol WhatsApp berisi nama produk
  var pesan = 'Halo cabil.rent, saya ingin tanya tentang ' + produk.nama + '.';
  btnTanya.href = 'https://wa.me/' + btnTanya.dataset.wa + '?text=' + encodeURIComponent(pesan);

  function tandaiDiKeranjang() {
    btnTambah.textContent = 'Sudah di Keranjang';
  }

  var sudahAda = CabilCart.ambil().some(function (item) {
    return item.id === produk.id;
  });
  if (sudahAda) tandaiDiKeranjang();

  // Sewa Sekarang -> masuk keranjang lalu buka halaman keranjang
  // Tambah ke Keranjang -> masuk keranjang, tetap di halaman ini
  form.addEventListener('submit', function (event) {
    event.preventDefault();
    var aksi = event.submitter ? event.submitter.value : 'tambah';

    produk.tanggal = form.tanggal.value;
    CabilCart.tambah(produk);

    var csrfInput = form.querySelector('input[name="_token"]');
    if (csrfInput) {
      fetch('/keranjang/tambah', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfInput.value
        },
        body: JSON.stringify({
          produk_id: produk.id,
          tanggal_acara: produk.tanggal,
          jumlah: 1
        })
      }).catch(function () {});
    }

    if (aksi === 'sewa') {
      window.location.href = form.dataset.keranjang;
    } else {
      tandaiDiKeranjang();
    }
  });
});
