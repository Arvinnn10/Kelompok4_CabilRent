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

    var teks = 'Halo Kak admin cabil.rent, saya ' + nama + ' mau konsultasi seputar busana:\n\n' +
      '• Pilihan busana: ' + busana + '\n' +
      '• Rencana tanggal acara: ' + (tanggal || '-') + '\n' +
      '• Pertanyaan / pesan: ' + (pesan || '-') + '\n\n' +
      'Terima kasih kak!';

    var url = 'https://wa.me/' + form.dataset.wa + '?text=' + encodeURIComponent(teks);
    window.open(url, '_blank');
  });
});
