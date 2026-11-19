// Dashboard admin: menu notifikasi, menu akun,
// dan konfirmasi keluar sebelum logout.
document.addEventListener('DOMContentLoaded', function () {
  var modal = document.getElementById('modal-logout');

  // Menu dropdown notifikasi dan akun; hanya satu yang terbuka
  var dropdowns = [
    { tombol: document.getElementById('btn-notif'), menu: document.getElementById('menu-notif') },
    { tombol: document.getElementById('btn-akun'), menu: document.getElementById('menu-akun') }
  ];

  function tutupSemua() {
    dropdowns.forEach(function (d) {
      d.menu.hidden = true;
      d.tombol.setAttribute('aria-expanded', 'false');
    });
  }

  dropdowns.forEach(function (d) {
    d.tombol.addEventListener('click', function (e) {
      e.stopPropagation();
      var akanBuka = d.menu.hidden;
      tutupSemua();
      d.menu.hidden = !akanBuka;
      d.tombol.setAttribute('aria-expanded', String(akanBuka));
    });
    d.menu.addEventListener('click', function (e) {
      e.stopPropagation();
    });
  });

  document.addEventListener('click', tutupSemua);

  // Tandai semua notifikasi sudah dibaca
  document.getElementById('btn-baca').addEventListener('click', function () {
    document.querySelectorAll('.notif-item.is-unread').forEach(function (item) {
      item.classList.remove('is-unread');
    });
    var dot = document.getElementById('notif-dot');
    if (dot) dot.remove();
  });

  // Konfirmasi keluar (dari menu akun dan tombol Keluar di sidebar)
  function bukaModal() {
    tutupSemua();
    modal.hidden = false;
    modal.querySelector('.btn-batal').focus();
  }

  function tutupModal() {
    modal.hidden = true;
  }

  document.querySelectorAll('[data-open-logout]').forEach(function (tombol) {
    tombol.addEventListener('click', function (e) {
      e.stopPropagation();
      bukaModal();
    });
  });

  modal.querySelector('[data-close-logout]').addEventListener('click', tutupModal);

  // Klik area gelap di luar kotak = batal
  modal.addEventListener('click', function (e) {
    if (e.target === modal) tutupModal();
  });

  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;
    if (!modal.hidden) {
      tutupModal();
    } else {
      tutupSemua();
    }
  });
});
