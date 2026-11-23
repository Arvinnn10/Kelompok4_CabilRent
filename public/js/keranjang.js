// Halaman keranjang belanja
document.addEventListener('DOMContentLoaded', function () {
  var list = document.getElementById('cart-list');
  var template = document.getElementById('cart-item-template');
  var emptyBox = document.getElementById('cart-empty');
  var summaryRows = document.getElementById('summary-rows');
  var btnLanjut = document.getElementById('btn-lanjut');

  function tampilkan() {
    var daftar = CabilCart.ambil();
    list.innerHTML = '';

    daftar.forEach(function (item) {
      var li = template.content.firstElementChild.cloneNode(true);
      var img = li.querySelector('img');

      img.src = item.gambar;
      img.alt = item.nama;
      img.addEventListener('error', function () {
        img.classList.add('is-missing');
      });

      var qty = parseInt(item.jumlah || item.qty, 10) || 1;
      li.querySelector('.cart-item-name').textContent = item.nama;
      li.querySelector('.cart-item-meta').textContent = item.kategori + ' · ' + (item.keterangan || '');
      li.querySelector('.cart-item-durasi').textContent = 'Sewa ' + (item.durasi || 3) + ' hari' + (qty > 1 ? ' · x' + qty : '');
      li.querySelector('.cart-item-price').textContent = CabilCart.keRupiah((parseInt(item.harga, 10) || 0) * qty);
      li.querySelector('.cart-item-hapus').addEventListener('click', function () {
        CabilCart.hapus(item.id);
        tampilkan();
      });

      list.appendChild(li);
    });

    // Ringkasan biaya
    var subtotal = daftar.reduce(function (total, item) {
      var qty = parseInt(item.jumlah || item.qty, 10) || 1;
      return total + ((parseInt(item.harga, 10) || 0) * qty);
    }, 0);
    var totalQty = daftar.reduce(function (total, item) {
      var qty = parseInt(item.jumlah || item.qty, 10) || 1;
      return total + qty;
    }, 0);
    var deposit = totalQty * CabilCart.DEPOSIT_PER_PRODUK;
    var total = subtotal + deposit;

    document.getElementById('summary-subtotal').textContent = CabilCart.keRupiah(subtotal);
    document.getElementById('summary-deposit').textContent = CabilCart.keRupiah(deposit);
    document.getElementById('summary-total').textContent = CabilCart.keRupiah(total);

    // Keranjang kosong vs berisi
    var kosong = daftar.length === 0;
    emptyBox.hidden = !kosong;
    summaryRows.hidden = kosong;
    btnLanjut.classList.toggle('is-disabled', kosong);
    btnLanjut.setAttribute('aria-disabled', kosong);
  }

  // Lanjut ke Pemesanan -> buka halaman Data Pemesanan (/pemesanan).
  // Keranjang kosong: tombol tidak melakukan apa-apa.
  if (btnLanjut) {
    btnLanjut.addEventListener('click', function (e) {
      if (btnLanjut.classList.contains('is-disabled')) {
        e.preventDefault();
      }
    });
  }

  tampilkan();
});
