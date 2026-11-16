// Halaman keranjang belanja
document.addEventListener('DOMContentLoaded', function () {
  var list = document.getElementById('cart-list');
  var template = document.getElementById('cart-item-template');
  var emptyBox = document.getElementById('cart-empty');
  var summaryRows = document.getElementById('summary-rows');
  var btnLanjut = document.getElementById('btn-lanjut');

  // Modal checkout
  var modal = document.getElementById('checkout-modal');
  var modalClose = document.getElementById('checkout-modal-close');
  var modalCancel = document.getElementById('btn-modal-cancel');
  var checkoutForm = document.getElementById('checkout-form');
  var checkoutItemsInput = document.getElementById('checkout-items');
  var checkoutItemCount = document.getElementById('checkout-item-count');
  var checkoutTotalPrice = document.getElementById('checkout-total-price');

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

      li.querySelector('.cart-item-name').textContent = item.nama;
      li.querySelector('.cart-item-meta').textContent = item.kategori + ' · ' + (item.keterangan || '');
      li.querySelector('.cart-item-durasi').textContent = 'Sewa ' + (item.durasi || 3) + ' hari';
      li.querySelector('.cart-item-price').textContent = CabilCart.keRupiah(item.harga);
      li.querySelector('.cart-item-hapus').addEventListener('click', function () {
        CabilCart.hapus(item.id);
        tampilkan();
      });

      list.appendChild(li);
    });

    // Ringkasan biaya
    var subtotal = daftar.reduce(function (total, item) {
      return total + (parseInt(item.harga, 10) || 0);
    }, 0);
    var deposit = daftar.length * CabilCart.DEPOSIT_PER_PRODUK;
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

  function tutupModal() {
    if (modal) modal.style.display = 'none';
  }

  function bukaModal() {
    var daftar = CabilCart.ambil();
    if (daftar.length === 0) return;

    var subtotal = daftar.reduce(function (total, item) {
      return total + (parseInt(item.harga, 10) || 0);
    }, 0);
    var deposit = daftar.length * CabilCart.DEPOSIT_PER_PRODUK;
    var total = subtotal + deposit;

    if (checkoutItemsInput) checkoutItemsInput.value = JSON.stringify(daftar);
    if (checkoutItemCount) checkoutItemCount.textContent = daftar.length + ' busana';
    if (checkoutTotalPrice) checkoutTotalPrice.textContent = CabilCart.keRupiah(total);

    // Ambil tanggal dari item pertama jika sudah dipilih sebelumnya
    var itemDenganTanggal = daftar.find(function(i) { return i.tanggal; });
    var inputTgl = document.getElementById('tanggal_acara');
    if (inputTgl && itemDenganTanggal && itemDenganTanggal.tanggal) {
      inputTgl.value = itemDenganTanggal.tanggal;
    }

    if (modal) modal.style.display = 'flex';
  }

  if (btnLanjut) {
    btnLanjut.addEventListener('click', function (e) {
      e.preventDefault();
      if (btnLanjut.classList.contains('is-disabled')) return;
      bukaModal();
    });
  }

  if (modalClose) modalClose.addEventListener('click', tutupModal);
  if (modalCancel) modalCancel.addEventListener('click', tutupModal);
  if (modal) {
    modal.addEventListener('click', function (e) {
      if (e.target === modal) tutupModal();
    });
  }

  if (checkoutForm) {
    checkoutForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var submitBtn = document.getElementById('btn-modal-submit');
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Memproses Pesanan...';
      }

      var formData = new FormData(checkoutForm);
      fetch(checkoutForm.action, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
      })
      .then(function (res) {
        return res.json();
      })
      .then(function (data) {
        if (data.success && data.whatsapp_url) {
          // Kosongkan keranjang setelah berhasil disimpan ke DB
          localStorage.removeItem('cabilrent-keranjang');
          window.location.href = data.whatsapp_url;
        } else {
          alert(data.message || 'Gagal memproses pesanan.');
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Konfirmasi & Chat WhatsApp';
          }
        }
      })
      .catch(function () {
        checkoutForm.submit();
      });
    });
  }

  tampilkan();
});
