// Transisi halus perpindahan halaman CabilRent
(function () {
  'use strict';

  function resetTransisi() {
    if (document.body) {
      document.body.classList.remove('is-exiting');
    }
  }

  // Pulihkan tampilan secara instan saat dokumen siap, halaman dimuat, atau dibuka dari bfcache
  window.addEventListener('pageshow', resetTransisi);
  window.addEventListener('DOMContentLoaded', resetTransisi);
  window.addEventListener('load', resetTransisi);

  document.addEventListener('DOMContentLoaded', function () {
    // Tandai halaman admin agar animasi terfokus pada konten utama (sidebar tetap stabil)
    if (document.querySelector('.app-container, .sidebar')) {
      document.body.classList.add('is-admin-panel');
    }

    var exitTimer = null;

    document.addEventListener('click', function (e) {
      var link = e.target.closest('a');
      if (!link) return;

      var href = link.getAttribute('href');
      if (!href) return;

      // Abaikan jika membuka tab baru, unduhan, protokol eksternal, atau tombol selain klik kiri biasa
      if (
        e.ctrlKey ||
        e.metaKey ||
        e.shiftKey ||
        e.altKey ||
        e.button !== 0 ||
        link.target === '_blank' ||
        link.hasAttribute('download') ||
        link.classList.contains('no-transition') ||
        href.indexOf('javascript:') === 0 ||
        href.indexOf('mailto:') === 0 ||
        href.indexOf('tel:') === 0 ||
        href.indexOf('#') === 0
      ) {
        return;
      }

      var tujuanUrl;
      try {
        tujuanUrl = new URL(link.href, window.location.origin);
      } catch (err) {
        return;
      }

      // Pastikan link berada dalam domain web yang sama
      if (tujuanUrl.origin !== window.location.origin) return;

      // Jika hanya scroll ke anchor pada halaman yang sama, biarkan berjalan alami
      if (
        tujuanUrl.pathname === window.location.pathname &&
        tujuanUrl.search === window.location.search &&
        tujuanUrl.hash
      ) {
        return;
      }

      // Berikan efek transisi halus keluar
      document.body.classList.add('is-exiting');

      // Failsafe: jika navigasi dibatalkan atau terhenti, pulihkan otomatis dalam 1.2 detik
      clearTimeout(exitTimer);
      exitTimer = setTimeout(resetTransisi, 1200);
    });
  });
})();
