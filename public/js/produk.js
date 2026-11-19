// Halaman produk admin: panel Tambah / Ubah produk (satu form untuk keduanya),
// pratinjau foto, dan konfirmasi sebelum menghapus.
document.addEventListener('DOMContentLoaded', function () {
  var drawer = document.getElementById('drawer');
  var overlay = document.getElementById('drawer-overlay');
  var form = document.getElementById('form-produk');
  var judul = document.getElementById('drawer-judul');
  var inputMethod = document.getElementById('field-method');
  var inputFoto = document.getElementById('field-foto');
  var preview = document.getElementById('pf-preview');
  var teksUpload = document.getElementById('pf-upload-teks');

  function tampilkanFoto(url) {
    if (url) {
      preview.src = url;
      preview.hidden = false;
      teksUpload.hidden = true;
    } else {
      preview.removeAttribute('src');
      preview.hidden = true;
      teksUpload.hidden = false;
    }
  }

  function buka() {
    overlay.hidden = false;
    drawer.hidden = false;
    document.getElementById('field-nama').focus();
  }

  function tutup() {
    drawer.hidden = true;
    overlay.hidden = true;
  }

  // Mode Tambah: form kosong, dikirim ke route produk.store
  function modeTambah() {
    form.reset();
    form.action = form.dataset.actionTambah;
    inputMethod.disabled = true;
    judul.textContent = 'Tambah Produk';
    tampilkanFoto('');
    document.getElementById('field-tampil').checked = true;
    buka();
  }

  // Mode Ubah: form diisi dari data-* tombol pensil, dikirim sebagai PUT
  function modeUbah(tombol) {
    var d = tombol.dataset;
    form.reset();
    form.action = d.action;
    inputMethod.disabled = false;
    judul.textContent = 'Ubah Produk';

    document.getElementById('field-nama').value = d.nama;
    document.getElementById('field-kategori').value = d.kategori;
    document.getElementById('field-ukuran').value = d.ukuran;
    document.getElementById('field-harga').value = d.harga;
    document.getElementById('field-stok').value = d.stok;
    document.getElementById('field-desk').value = d.desk;
    document.getElementById('field-tampil').checked = d.tampil === '1';
    tampilkanFoto(d.foto);
    buka();
  }

  document.getElementById('btn-tambah').addEventListener('click', modeTambah);

  document.querySelectorAll('.btn-ubah').forEach(function (tombol) {
    tombol.addEventListener('click', function () {
      modeUbah(tombol);
    });
  });

  // Pratinjau foto yang baru dipilih
  inputFoto.addEventListener('change', function () {
    var file = inputFoto.files && inputFoto.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function (e) {
      tampilkanFoto(e.target.result);
    };
    reader.readAsDataURL(file);
  });

  document.getElementById('drawer-x').addEventListener('click', tutup);
  document.getElementById('drawer-batal').addEventListener('click', tutup);
  overlay.addEventListener('click', tutup);

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !drawer.hidden) tutup();
  });

  // Konfirmasi sebelum menghapus produk
  document.querySelectorAll('form[data-konfirmasi]').forEach(function (f) {
    f.addEventListener('submit', function (e) {
      if (!window.confirm(f.dataset.konfirmasi)) e.preventDefault();
    });
  });
});
