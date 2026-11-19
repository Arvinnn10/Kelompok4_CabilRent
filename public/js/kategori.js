// Halaman kategori admin: kotak dialog Tambah / Ubah kategori (satu form untuk keduanya)
// dan konfirmasi sebelum menghapus.
document.addEventListener('DOMContentLoaded', function () {
  var modal = document.getElementById('modal-kategori');
  var form = document.getElementById('form-kategori');
  var judul = document.getElementById('modal-kategori-judul');
  var inputMethod = document.getElementById('field-method');
  var inputNama = document.getElementById('field-nama');
  var inputDeskripsi = document.getElementById('field-deskripsi');

  function buka() {
    modal.hidden = false;
    inputNama.focus();
  }

  function tutup() {
    modal.hidden = true;
  }

  // Mode Tambah: form kosong, dikirim ke route kategori.store
  document.getElementById('btn-tambah').addEventListener('click', function () {
    form.reset();
    form.action = form.dataset.actionTambah;
    inputMethod.disabled = true;
    judul.textContent = 'Tambah Kategori';
    buka();
  });

  // Mode Ubah: diisi dari data-* tombol pensil, dikirim sebagai PUT
  document.querySelectorAll('.btn-ubah').forEach(function (tombol) {
    tombol.addEventListener('click', function () {
      form.reset();
      form.action = tombol.dataset.action;
      inputMethod.disabled = false;
      judul.textContent = 'Ubah Kategori';
      inputNama.value = tombol.dataset.nama;
      inputDeskripsi.value = tombol.dataset.deskripsi;
      buka();
    });
  });

  document.getElementById('kt-batal').addEventListener('click', tutup);

  // Klik area gelap di luar kotak = batal
  modal.addEventListener('click', function (e) {
    if (e.target === modal) tutup();
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !modal.hidden) tutup();
  });

  // Konfirmasi sebelum menghapus kategori
  document.querySelectorAll('form[data-konfirmasi]').forEach(function (f) {
    f.addEventListener('submit', function (e) {
      if (!window.confirm(f.dataset.konfirmasi)) e.preventDefault();
    });
  });
});
