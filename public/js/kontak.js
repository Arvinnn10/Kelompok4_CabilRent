// Form halaman kontak: isi form disusun jadi pesan lalu dibuka di WhatsApp.
// Nomor tujuan diambil dari atribut data-wa pada <form id="kontak-form">.
document.addEventListener('DOMContentLoaded', function () {
  var form = document.getElementById('kontak-form');
  if (!form) return;

  form.addEventListener('submit', function (event) {
    event.preventDefault();

    var nama = form.nama.value.trim();
    var busana = form.busana.value;
    var tanggal = form.tanggal.value;
    var pesan = form.pesan.value.trim();

    var teks = 'Halo cabil.rent, saya ' + nama + '.\n' +
      'Busana yang diminati: ' + busana + '\n' +
      'Tanggal acara: ' + (tanggal || '-') + '\n' +
      'Pesan: ' + (pesan || '-');

    var url = 'https://wa.me/' + form.dataset.wa + '?text=' + encodeURIComponent(teks);
    window.open(url, '_blank');
  });
});
