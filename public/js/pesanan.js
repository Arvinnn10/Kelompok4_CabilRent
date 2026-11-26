// Halaman pesanan admin: tombol mata membuka panel detail,
// diisi dari atribut data-* pada tombol di baris tabel.
document.addEventListener('DOMContentLoaded', function () {
  var drawer = document.getElementById('drawer');
  var overlay = document.getElementById('drawer-overlay');
  var form = document.getElementById('drawer-form');
  var badge = document.getElementById('drawer-badge');
  var foto = document.getElementById('drawer-foto');

  var LABEL_STATUS = {
    pending: 'Menunggu',
    dikonfirmasi: 'Dikonfirmasi',
    sedang_dipinjam: 'Sedang Dipinjam',
    selesai: 'Selesai',
    dibatalkan: 'Dibatalkan'
  };

  function rupiah(angka) {
    return 'Rp ' + Math.round(angka).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  }

  function isi(id, teks) {
    document.getElementById(id).textContent = teks;
  }

  // "Bella Safira" -> "BS"
  function inisial(nama) {
    var kata = nama.trim().split(/\s+/);
    return ((kata[0] || '').charAt(0) + (kata[1] || '').charAt(0)).toUpperCase();
  }

  // "0812-3456-0010" -> "6281234560010" untuk tautan wa.me
  function nomorWa(nomor) {
    var angka = nomor.replace(/\D/g, '');
    if (angka.indexOf('0') === 0) angka = '62' + angka.slice(1);
    return angka;
  }

  function buka(tombol) {
    var d = tombol.dataset;
    var total = parseInt(d.total, 10) || 0;
    var harga = parseInt(d.harga, 10) || 0;
    var jumlah = parseInt(d.jumlah, 10) || 1;
    var subtotal = harga * jumlah;
    var deposit = Math.max(0, total - subtotal);

    isi('drawer-id', d.id);
    badge.textContent = LABEL_STATUS[d.status] || d.status;
    badge.className = 'badge status-' + d.status;

    isi('drawer-avatar', inisial(d.nama));
    isi('drawer-nama', d.nama);
    isi('drawer-wa', d.whatsapp);
    document.getElementById('drawer-chat').href = d.whatsapp ? 'https://wa.me/' + nomorWa(d.whatsapp) : '#';

    isi('drawer-tgl', d.tglAcara);
    isi('drawer-dipesan', d.dipesan);

    // Foto dan nama baju otomatis dari produk pesanan
    isi('drawer-produk', d.produk);
    isi('drawer-harga', rupiah(harga));
    foto.classList.remove('is-missing');
    foto.alt = d.produk;
    if (d.foto) {
      foto.src = d.foto;
    } else {
      foto.removeAttribute('src');
      foto.classList.add('is-missing');
    }

    isi('drawer-subtotal', rupiah(subtotal));
    isi('drawer-deposit', rupiah(deposit));
    isi('drawer-total', rupiah(total));

    document.getElementById('drawer-status').value = d.status;
    form.action = d.action;

    overlay.hidden = false;
    drawer.hidden = false;
    document.getElementById('drawer-x').focus();
  }

  function tutup() {
    drawer.hidden = true;
    overlay.hidden = true;
  }

  foto.addEventListener('error', function () {
    foto.classList.add('is-missing');
  });

  document.querySelectorAll('.btn-lihat').forEach(function (tombol) {
    tombol.addEventListener('click', function () {
      buka(tombol);
    });
  });

  document.getElementById('drawer-x').addEventListener('click', tutup);
  var btnDrawerTutup = document.getElementById('drawer-tutup');
  if (btnDrawerTutup) {
    btnDrawerTutup.addEventListener('click', tutup);
  }
  overlay.addEventListener('click', tutup);

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !drawer.hidden) tutup();
  });
});
