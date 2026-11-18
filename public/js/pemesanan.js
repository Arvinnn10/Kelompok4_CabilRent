// Halaman pemesanan: menampilkan ringkasan isi keranjang (js/cart.js),
// memvalidasi form, lalu membuka WhatsApp dengan pesan pesanan yang sudah terisi.
document.addEventListener('DOMContentLoaded', function () {
  var form = document.getElementById('order-form');
  var btnKirim = document.getElementById('btn-kirim');
  var list = document.getElementById('order-items');
  var template = document.getElementById('order-item-template');
  var emptyBox = document.getElementById('order-empty');
  var rows = document.getElementById('order-rows');
  var inputTanggal = document.getElementById('tanggal');

  var daftar = CabilCart.ambil();
  var subtotal = daftar.reduce(function (total, item) {
    return total + item.harga;
  }, 0);
  var deposit = daftar.length * CabilCart.DEPOSIT_PER_PRODUK;
  var total = subtotal + deposit;

  // Tanggal acara tidak boleh sebelum hari ini
  var hariIni = new Date();
  inputTanggal.min = hariIni.getFullYear() + '-' +
    String(hariIni.getMonth() + 1).padStart(2, '0') + '-' +
    String(hariIni.getDate()).padStart(2, '0');

  // Ringkasan pesanan
  daftar.forEach(function (item) {
    var li = template.content.firstElementChild.cloneNode(true);
    var img = li.querySelector('img');
    img.src = item.gambar;
    img.alt = item.nama;
    img.addEventListener('error', function () {
      img.classList.add('is-missing');
    });
    li.querySelector('.order-item-name').textContent = item.nama;
    li.querySelector('.order-item-meta').textContent = item.keterangan + ' · ' + item.durasi + ' hari';
    li.querySelector('.order-item-price').textContent = CabilCart.keRupiah(item.harga);
    list.appendChild(li);
  });

  document.getElementById('summary-subtotal').textContent = CabilCart.keRupiah(subtotal);
  document.getElementById('summary-deposit').textContent = CabilCart.keRupiah(deposit);
  document.getElementById('summary-total').textContent = CabilCart.keRupiah(total);

  var kosong = daftar.length === 0;
  emptyBox.hidden = !kosong;
  rows.hidden = kosong;
  btnKirim.disabled = kosong;

  // Validasi
  function setError(nama, pesan) {
    var field = form.elements[nama].closest('.order-field');
    field.classList.toggle('has-error', !!pesan);
    form.querySelector('[data-error-for="' + nama + '"]').textContent = pesan || '';
  }

  function valid() {
    var nama = form.elements.nama.value.trim();
    var wa = form.elements.whatsapp.value.replace(/[\s-]/g, '');
    var tanggal = form.elements.tanggal.value;

    var namaOk = nama !== '';
    setError('nama', namaOk ? '' : 'Nama wajib diisi.');

    var waOk = /^(\+62|62|0)8\d{7,12}$/.test(wa);
    setError('whatsapp', waOk ? '' : 'Masukkan nomor WhatsApp yang valid, contoh 081234567890.');

    var tglOk = tanggal !== '' && tanggal >= inputTanggal.min;
    setError('tanggal', tglOk ? '' : 'Pilih tanggal acara (hari ini atau setelahnya).');

    return namaOk && waOk && tglOk;
  }

  // "2026-12-05" -> "5 Desember 2026"
  function formatTanggal(iso) {
    var bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli',
      'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    var p = iso.split('-');
    return parseInt(p[2], 10) + ' ' + bulan[parseInt(p[1], 10) - 1] + ' ' + p[0];
  }

  function susunPesan() {
    var baris = ['Halo Cabil.Rent, saya ingin memesan:', ''];

    daftar.forEach(function (item, i) {
      baris.push((i + 1) + '. ' + item.nama + ' (' + item.keterangan + ') - ' +
        item.durasi + ' hari - ' + CabilCart.keRupiah(item.harga));
    });

    baris.push('');
    baris.push('Subtotal sewa: ' + CabilCart.keRupiah(subtotal));
    baris.push('Deposit (dikembalikan): ' + CabilCart.keRupiah(deposit));
    baris.push('Total dibayar awal: ' + CabilCart.keRupiah(total));
    baris.push('');
    baris.push('Nama: ' + form.elements.nama.value.trim());
    baris.push('No. WhatsApp: ' + form.elements.whatsapp.value.trim());
    baris.push('Tanggal acara: ' + formatTanggal(form.elements.tanggal.value));

    var catatan = form.elements.catatan.value.trim();
    if (catatan) {
      baris.push('Catatan: ' + catatan);
    }

    baris.push('');
    baris.push('Mohon dicek ketersediaannya. Terima kasih.');
    return baris.join('\n');
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    if (kosong || !valid()) {
      return;
    }
    var url = 'https://wa.me/' + btnKirim.dataset.wa + '?text=' + encodeURIComponent(susunPesan());
    window.open(url, '_blank', 'noopener');
  });
});
