// Penyimpanan keranjang sewa (dipakai halaman koleksi dan keranjang).
// Isi keranjang disimpan di localStorage browser dengan kunci 'cabilrent-keranjang'.
// Setiap produk: { id, nama, kategori, keterangan, harga, gambar, durasi, tanggal }
var CabilCart = (function () {
  var KUNCI = 'cabilrent-keranjang';
  var DEPOSIT_PER_PRODUK = 100000;

  function ambil() {
    try {
      return JSON.parse(localStorage.getItem(KUNCI)) || [];
    } catch (e) {
      return [];
    }
  }

  function simpan(daftar) {
    try {
      localStorage.setItem(KUNCI, JSON.stringify(daftar));
    } catch (e) {
      // Penyimpanan diblokir browser: keranjang tidak tersimpan
    }
  }

  // Menambah produk; produk yang sama tidak dimasukkan dua kali
  function tambah(produk) {
    var daftar = ambil();
    var sudahAda = daftar.some(function (item) {
      return item.id === produk.id;
    });
    if (!sudahAda) {
      daftar.push(produk);
      simpan(daftar);
    }
    return !sudahAda;
  }

  function hapus(id) {
    simpan(ambil().filter(function (item) {
      return item.id !== id;
    }));
  }

  // "Rp 50.000/3 hari" -> 50000
  function keAngka(teksHarga) {
    return parseInt(teksHarga.split('/')[0].replace(/\D/g, ''), 10) || 0;
  }

  // 250000 -> "Rp 250.000"
  function keRupiah(angka) {
    return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  }

  return {
    ambil: ambil,
    tambah: tambah,
    hapus: hapus,
    keAngka: keAngka,
    keRupiah: keRupiah,
    DEPOSIT_PER_PRODUK: DEPOSIT_PER_PRODUK
  };
})();
