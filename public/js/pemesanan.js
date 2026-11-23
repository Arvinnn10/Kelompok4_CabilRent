// Halaman pemesanan: menampilkan ringkasan isi keranjang (js/cart.js),
// memvalidasi form, menyimpan pesanan ke sistem admin, lalu membuka WhatsApp
// dengan pesan pesanan yang sudah terisi.
document.addEventListener('DOMContentLoaded', function () {
  var form = document.getElementById('order-form');
  var btnKirim = document.getElementById('btn-kirim');
  var list = document.getElementById('order-items');
  var template = document.getElementById('order-item-template');
  var emptyBox = document.getElementById('order-empty');
  var rows = document.getElementById('order-rows');
  var inputTanggal = document.getElementById('tanggal');

  var modalOverlay = document.getElementById('stock-modal-overlay');
  var modalTitle = document.getElementById('stock-modal-title');
  var modalContent = document.getElementById('stock-modal-content');
  var btnModalBatal = document.getElementById('btn-stock-batal');
  var btnModalLanjut = document.getElementById('btn-stock-lanjut');

  var daftar = [];
  var subtotal = 0;
  var deposit = 0;
  var total = 0;
  var kosong = true;

  function muatRingkasan() {
    daftar = CabilCart.ambil();
    list.innerHTML = '';

    subtotal = daftar.reduce(function (tot, item) {
      var qty = item.jumlah || item.qty || 1;
      return tot + ((parseInt(item.harga, 10) || 0) * qty);
    }, 0);

    var totalQty = daftar.reduce(function (tot, item) {
      var qty = item.jumlah || item.qty || 1;
      return tot + qty;
    }, 0);

    deposit = totalQty * CabilCart.DEPOSIT_PER_PRODUK;
    total = subtotal + deposit;

    daftar.forEach(function (item) {
      var li = template.content.firstElementChild.cloneNode(true);
      var img = li.querySelector('img');
      img.src = item.gambar;
      img.alt = item.nama;
      img.addEventListener('error', function () {
        img.classList.add('is-missing');
      });
      var qty = item.jumlah || item.qty || 1;
      var durasi = (item.durasi || 3) + ' hari';
      var metaTeks = (item.ukuran ? item.ukuran + ' · ' : '') + durasi + (qty > 1 ? ' · x' + qty : '');
      li.querySelector('.order-item-name').textContent = item.nama;
      li.querySelector('.order-item-meta').textContent = metaTeks;
      li.querySelector('.order-item-price').textContent = CabilCart.keRupiah((parseInt(item.harga, 10) || 0) * qty);
      list.appendChild(li);
    });

    document.getElementById('summary-subtotal').textContent = CabilCart.keRupiah(subtotal);
    document.getElementById('summary-deposit').textContent = CabilCart.keRupiah(deposit);
    document.getElementById('summary-total').textContent = CabilCart.keRupiah(total);

    kosong = daftar.length === 0;
    emptyBox.hidden = !kosong;
    rows.hidden = kosong;
    btnKirim.disabled = kosong;
  }

  // Tanggal acara tidak boleh sebelum hari ini
  var hariIni = new Date();
  inputTanggal.min = hariIni.getFullYear() + '-' +
    String(hariIni.getMonth() + 1).padStart(2, '0') + '-' +
    String(hariIni.getDate()).padStart(2, '0');

  // Isi otomatis tanggal yang sudah dipilih di halaman detail
  var denganTanggal = CabilCart.ambil().find(function (item) { return item.tanggal; });
  if (denganTanggal && denganTanggal.tanggal >= inputTanggal.min) {
    inputTanggal.value = denganTanggal.tanggal;
  }

  muatRingkasan();

  // Validasi form
  function setError(nama, pesan) {
    var field = form.elements[nama].closest('.order-field');
    field.classList.toggle('has-error', !!pesan);
    form.querySelector('[data-error-for="' + nama + '"]').textContent = pesan || '';
  }

  function valid() {
    var nama = form.elements.nama.value.trim();
    var wa = form.elements.whatsapp.value.replace(/[\s-]/g, '');
    var domisili = form.elements.domisili ? form.elements.domisili.value.trim() : '';
    var tbBb = form.elements.tb_bb ? form.elements.tb_bb.value.trim() : '';
    var tanggal = form.elements.tanggal.value;

    var namaOk = nama !== '';
    setError('nama', namaOk ? '' : 'Nama wajib diisi.');

    var waOk = /^(\+62|62|0)8\d{7,12}$/.test(wa);
    setError('whatsapp', waOk ? '' : 'Masukkan nomor WhatsApp yang valid, contoh 081234567890.');

    var domisiliOk = domisili !== '';
    if (form.elements.domisili) {
      setError('domisili', domisiliOk ? '' : 'Domisili kota wajib diisi.');
    }

    var tbBbOk = tbBb !== '';
    if (form.elements.tb_bb) {
      setError('tb_bb', tbBbOk ? '' : 'Tinggi dan berat badan wajib diisi.');
    }

    var tglOk = tanggal !== '' && tanggal >= inputTanggal.min;
    setError('tanggal', tglOk ? '' : 'Pilih tanggal acara (hari ini atau setelahnya).');

    return namaOk && waOk && domisiliOk && tbBbOk && tglOk;
  }

  // "2026-12-05" -> "5 Desember 2026"
  function formatTanggal(iso) {
    var bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli',
      'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    var p = iso.split('-');
    return parseInt(p[2], 10) + ' ' + bulan[parseInt(p[1], 10) - 1] + ' ' + p[0];
  }

  function susunPesan(nomorPesanan) {
    var baris = ['*FORMAT SEWA CABIL.RENT*', ''];
    if (nomorPesanan) {
      baris.push('No. Invoice: #' + nomorPesanan);
    }

    baris.push('Nama: ' + form.elements.nama.value.trim());
    baris.push('No. WhatsApp: ' + form.elements.whatsapp.value.trim());
    if (form.elements.domisili) {
      baris.push('Domisili: ' + form.elements.domisili.value.trim());
    }
    if (form.elements.instagram && form.elements.instagram.value.trim()) {
      baris.push('Instagram: ' + form.elements.instagram.value.trim());
    }
    if (form.elements.tb_bb) {
      baris.push('TB/BB: ' + form.elements.tb_bb.value.trim());
    }
    baris.push('Tanggal acara: ' + formatTanggal(form.elements.tanggal.value));
    baris.push('');
    baris.push('*Busana:*');

    daftar.forEach(function (item, i) {
      var qty = item.jumlah || item.qty || 1;
      baris.push((i + 1) + '. ' + item.nama + (item.ukuran ? ' (' + item.ukuran + ')' : '') +
        (qty > 1 ? ' x' + qty : '') + ' - ' + (item.durasi || 3) + ' hari - ' +
        CabilCart.keRupiah((parseInt(item.harga, 10) || 0) * qty));
    });

    baris.push('');
    baris.push('Subtotal sewa: ' + CabilCart.keRupiah(subtotal));
    baris.push('Deposit (dikembalikan): ' + CabilCart.keRupiah(deposit));
    baris.push('Total dibayar awal: ' + CabilCart.keRupiah(total));

    var catatan = form.elements.catatan ? form.elements.catatan.value.trim() : '';
    if (catatan) {
      baris.push('');
      baris.push('Catatan tambahan: ' + catatan);
    }

    baris.push('');
    baris.push('Halo admin cabil.rent, saya mau sewa busana ini. Apakah masih tersedia? Terima kasih.');
    return baris.join('\n');
  }

  function bukaWhatsApp(nomorPesanan) {
    var waNumber = btnKirim.dataset.wa || '6287894302894';
    var url = 'https://wa.me/' + waNumber + '?text=' + encodeURIComponent(susunPesan(nomorPesanan));
    window.location.href = url;
  }

  // Pengelolaan modal pop-up konfirmasi stok
  function tutupModal() {
    if (modalOverlay) {
      modalOverlay.hidden = true;
    }
  }

  function bukaModalNotifikasi(judul, pesan) {
    if (!modalOverlay) return;
    modalTitle.textContent = judul;
    modalContent.innerHTML = '<p>' + pesan + '</p>';
    btnModalBatal.hidden = true;
    btnModalLanjut.textContent = 'Tutup';
    btnModalLanjut.onclick = function () {
      tutupModal();
    };
    modalOverlay.hidden = false;
  }

  function bukaModalKonfirmasiStok(hasil) {
    if (!modalOverlay) return;
    modalTitle.textContent = 'Konfirmasi Penyesuaian Stok';

    var html = '<p>Terdapat penyesuaian jumlah busana sesuai ketersediaan stok aktual:</p>';
    html += '<ul class="modal-adjust-list">';

    (hasil.adjustments || []).forEach(function (adj) {
      html += '<li class="modal-adjust-item">';
      html += '<div class="modal-adjust-item-name">' + adj.nama_produk + '</div>';
      html += '<div class="modal-adjust-item-detail">' + adj.keterangan + '</div>';
      html += '</li>';
    });

    html += '</ul>';
    html += '<p style="margin-top: 14px;">Apakah Anda ingin melanjutkan checkout dengan jumlah yang telah disesuaikan?</p>';

    modalContent.innerHTML = html;

    btnModalBatal.hidden = false;
    btnModalBatal.textContent = 'Batalkan';
    btnModalBatal.onclick = function () {
      tutupModal();
      btnKirim.disabled = false;
      btnKirim.textContent = 'Kirim Pesanan lewat Whatsapp';
    };

    btnModalLanjut.textContent = 'Lanjutkan Checkout';
    btnModalLanjut.onclick = function () {
      tutupModal();
      // Perbarui keranjang di localStorage dengan item yang telah disesuaikan
      if (hasil.adjusted_items && Array.isArray(hasil.adjusted_items)) {
        CabilCart.simpan(hasil.adjusted_items);
        muatRingkasan();
      }
      // Kirim ulang dengan konfirmasi
      kirimCheckout(true);
    };

    modalOverlay.hidden = false;
  }

  function kirimCheckout(isConfirmed) {
    if (kosong || !valid()) {
      return;
    }

    btnKirim.disabled = true;
    btnKirim.textContent = 'Memproses pesanan...';

    var data = new FormData();
    data.append('nama_lengkap', form.elements.nama.value.trim());
    data.append('no_whatsapp', form.elements.whatsapp.value.trim());
    data.append('domisili', form.elements.domisili ? form.elements.domisili.value.trim() : '-');
    data.append('tb_bb', form.elements.tb_bb ? form.elements.tb_bb.value.trim() : '-');
    data.append('instagram', form.elements.instagram ? form.elements.instagram.value.trim() : '-');
    data.append('tanggal_acara', form.elements.tanggal.value);
    data.append('catatan', form.elements.catatan ? form.elements.catatan.value.trim() : '');
    data.append('items', JSON.stringify(daftar));
    if (isConfirmed) {
      data.append('confirmed_adjustment', '1');
    }

    var token = document.querySelector('meta[name="csrf-token"]');

    fetch(form.dataset.checkout, {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': token ? token.content : ''
      },
      body: data
    })
      .then(function (res) {
        return res.json().catch(function () { return null; });
      })
      .then(function (hasil) {
        if (!hasil) {
          bukaWhatsApp(null);
          return;
        }

        // Skenario 1: Seluruh produk tidak tersedia
        if (hasil.all_unavailable) {
          btnKirim.disabled = false;
          btnKirim.textContent = 'Kirim Pesanan lewat Whatsapp';
          bukaModalNotifikasi('Produk Tidak Tersedia', hasil.message || 'Seluruh produk tidak tersedia.');
          return;
        }

        // Skenario 2: Butuh konfirmasi penyesuaian stok
        if (hasil.needs_confirmation) {
          btnKirim.disabled = false;
          btnKirim.textContent = 'Kirim Pesanan lewat Whatsapp';
          bukaModalKonfirmasiStok(hasil);
          return;
        }

        // Skenario 3: Error lain
        if (!hasil.success && hasil.message) {
          btnKirim.disabled = false;
          btnKirim.textContent = 'Kirim Pesanan lewat Whatsapp';
          bukaModalNotifikasi('Pemberitahuan', hasil.message);
          return;
        }

        // Skenario 4: Berhasil
        var kodePesanan = hasil.kode_pesanan || (hasil.order_ids ? hasil.order_ids[0] : null);
        if (kodePesanan) {
          try {
            localStorage.removeItem('cabilrent-keranjang');
          } catch (err) {}
        }

        if (hasil.whatsapp_url) {
          window.location.href = hasil.whatsapp_url;
        } else {
          bukaWhatsApp(kodePesanan);
        }
      })
      .catch(function () {
        btnKirim.disabled = false;
        btnKirim.textContent = 'Kirim Pesanan lewat Whatsapp';
        bukaWhatsApp(null);
      });
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    kirimCheckout(false);
  });
});
