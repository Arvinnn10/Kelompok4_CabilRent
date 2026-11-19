// Halaman pengguna admin: tombol mata membuka panel riwayat pesanan
// (diisi dari atribut data-* pada tombol di baris tabel), dan konfirmasi blokir.
document.addEventListener('DOMContentLoaded', function () {
  var drawer = document.getElementById('drawer');
  var overlay = document.getElementById('drawer-overlay');
  var badge = document.getElementById('drawer-badge');
  var daftar = document.getElementById('drawer-riwayat');
  var kosong = document.getElementById('drawer-riwayat-kosong');

  var LABEL_PENGGUNA = { aktif: 'Aktif', diblokir: 'Diblokir' };
  var LABEL_PESANAN = {
    pending: 'Menunggu',
    dikonfirmasi: 'Dikonfirmasi',
    selesai: 'Selesai',
    dibatalkan: 'Dibatalkan'
  };

  function isi(id, teks) {
    document.getElementById(id).textContent = teks;
  }

  function baris(item) {
    var li = document.createElement('li');

    var kiri = document.createElement('div');
    var id = document.createElement('p');
    id.className = 'pg-riwayat-id';
    id.textContent = item.id;
    var produk = document.createElement('p');
    produk.className = 'pg-riwayat-produk';
    produk.textContent = item.produk;
    var desk = document.createElement('p');
    desk.className = 'pg-riwayat-desk';
    desk.textContent = item.desk;
    kiri.appendChild(id);
    kiri.appendChild(produk);
    if (item.desk) kiri.appendChild(desk);

    var status = document.createElement('span');
    status.className = 'badge status-' + item.status;
    status.textContent = LABEL_PESANAN[item.status] || item.status;

    li.appendChild(kiri);
    li.appendChild(status);
    return li;
  }

  function buka(tombol) {
    var d = tombol.dataset;
    var riwayat = [];
    try {
      riwayat = JSON.parse(d.riwayat) || [];
    } catch (e) {
      riwayat = [];
    }

    isi('drawer-judul', d.nama);
    isi('drawer-avatar', d.inisial);
    isi('drawer-wa', d.whatsapp);
    isi('drawer-gabung', 'Bergabung ' + d.bergabung);
    isi('drawer-pesanan', d.pesanan);
    isi('drawer-total', d.total);

    badge.textContent = LABEL_PENGGUNA[d.status] || d.status;
    badge.className = 'badge status-' + d.status;

    daftar.innerHTML = '';
    riwayat.forEach(function (item) {
      daftar.appendChild(baris(item));
    });
    kosong.hidden = riwayat.length > 0;

    overlay.hidden = false;
    drawer.hidden = false;
    document.getElementById('drawer-x').focus();
  }

  function tutup() {
    drawer.hidden = true;
    overlay.hidden = true;
  }

  document.querySelectorAll('.btn-lihat').forEach(function (tombol) {
    tombol.addEventListener('click', function () {
      buka(tombol);
    });
  });

  document.getElementById('drawer-x').addEventListener('click', tutup);
  document.getElementById('drawer-tutup').addEventListener('click', tutup);
  overlay.addEventListener('click', tutup);

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !drawer.hidden) tutup();
  });

  // Konfirmasi sebelum memblokir / mengaktifkan pengguna
  document.querySelectorAll('form[data-konfirmasi]').forEach(function (f) {
    f.addEventListener('submit', function (e) {
      if (!window.confirm(f.dataset.konfirmasi)) e.preventDefault();
    });
  });
});
