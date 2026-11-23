// Halaman detail produk CabilRent.
// Data produk di-render langsung oleh server dari database MySQL.
// Script ini menangani interaktivitas: gambar, tombol WhatsApp, dan keranjang.
document.addEventListener('DOMContentLoaded', function () {
  var root = document.getElementById('detail-produk');
  if (!root) return;

  var form = document.getElementById('detail-form');
  var btnTambah = document.getElementById('btn-tambah-keranjang');
  var btnTanya = document.getElementById('btn-tanya');
  var gambar = document.getElementById('detail-gambar');

  var produk = {
    id: root.dataset.id || '',
    nama: root.dataset.nama || (document.getElementById('detail-nama') ? document.getElementById('detail-nama').textContent.trim() : ''),
    kategori: root.dataset.kategori || '',
    keterangan: root.dataset.keterangan || '',
    harga: parseInt(root.dataset.harga, 10) || 0,
    gambar: root.dataset.gambar || (gambar ? gambar.getAttribute('src') : ''),
    durasi: 3
  };

  // Pastikan kontainer utama terlihat
  root.style.visibility = 'visible';

  // Penanganan foto produk: jangan sembunyikan jika foto valid
  if (gambar) {
    function tandaiKosong() {
      gambar.classList.add('is-missing');
    }
    function tandaiAda() {
      gambar.classList.remove('is-missing');
    }

    gambar.addEventListener('error', tandaiKosong);
    gambar.addEventListener('load', tandaiAda);

    if (gambar.complete) {
      if (gambar.naturalWidth > 0) {
        tandaiAda();
      } else if (!gambar.getAttribute('src')) {
        tandaiKosong();
      }
    }
  }

  // Tombol Tanya via WhatsApp
  if (btnTanya) {
    var waNumber = btnTanya.dataset.wa || '6287894302894';
    var pesanTanya = 'Halo cabil.rent, saya ingin tanya ketersediaan sewa untuk busana ' + produk.nama + '.';
    btnTanya.href = 'https://wa.me/' + waNumber + '?text=' + encodeURIComponent(pesanTanya);
  }

  // Status tombol keranjang jika produk sudah ada di keranjang
  function tandaiDiKeranjang() {
    if (btnTambah) {
      btnTambah.textContent = 'Sudah di Keranjang';
    }
  }

  if (typeof CabilCart !== 'undefined') {
    var sudahAda = CabilCart.ambil().some(function (item) {
      return String(item.id) === String(produk.id);
    });
    if (sudahAda) tandaiDiKeranjang();
  }

  // Beli Langsung via WhatsApp
  var btnBeliWa = document.getElementById('btn-beli-wa');
  if (btnBeliWa && form) {
    btnBeliWa.addEventListener('click', function () {
      if (!form.reportValidity()) return;

      var waNumber = (btnTanya && btnTanya.dataset.wa) || '6287894302894';
      var hargaFormatted = (typeof CabilCart !== 'undefined') ? CabilCart.keRupiah(produk.harga) : ('Rp ' + produk.harga);

      var teks = [
        'Halo cabil.rent, saya ingin menyewa:',
        '',
        produk.nama + ' (' + produk.kategori + ')',
        'Harga: ' + hargaFormatted + ' / ' + produk.durasi + ' hari',
        'Tanggal acara: ' + form.tanggal.value,
        '',
        'Mohon dicek ketersediaannya. Terima kasih.'
      ].join('\n');

      window.open('https://wa.me/' + waNumber + '?text=' + encodeURIComponent(teks), '_blank', 'noopener');
    });
  }

  // Tambah ke Keranjang
  if (form) {
    form.addEventListener('submit', function (event) {
      event.preventDefault();
      var aksi = event.submitter ? event.submitter.value : 'tambah';

      produk.tanggal = form.tanggal.value;
      if (typeof CabilCart !== 'undefined') {
        CabilCart.tambah(produk);
      }

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

      tandaiDiKeranjang();

      if (aksi === 'sewa') {
        window.location.href = form.dataset.keranjang || '/keranjang';
      }
    });
  }

  // Bersihkan data sementara localStorage agar tidak mengontaminasi navigasi berikutnya
  try {
    localStorage.removeItem('cabilrent-produk-dipilih');
  } catch (e) {}
});
