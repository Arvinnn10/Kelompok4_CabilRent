/**
 * CabilRent - Automatic CSS Hot Reload Client
 * Memantau perubahan file public/css/*.css dan memperbarui <link rel="stylesheet">
 * langsung di DOM tanpa reload halaman (F5), tanpa reset scroll, tanpa kehilangan data form,
 * dan tanpa mengganggu JavaScript aktif.
 */
(function () {
  'use strict';

  if (typeof window === 'undefined' || typeof document === 'undefined') return;

  var currentScript = document.currentScript || document.querySelector('script[data-dev-endpoint]');
  var endpoint = (currentScript && currentScript.getAttribute('data-dev-endpoint')) || '/_css-dev/status';

  var lastKnownTimestamps = null;
  var isPolling = false;
  var pollIntervalMs = 3500; // Frekuensi optimal, ringan untuk server dan cepat mendeteksi perubahan
  var timerId = null;
  var consecutiveErrors = 0;

  // Notifikasi visual mini di pojok kanan bawah
  function showToast(fileName) {
    var toast = document.getElementById('cabil-css-hot-toast');
    if (!toast) {
      toast = document.createElement('div');
      toast.id = 'cabil-css-hot-toast';
      toast.style.position = 'fixed';
      toast.style.bottom = '24px';
      toast.style.right = '24px';
      toast.style.backgroundColor = '#623e2b';
      toast.style.color = '#ffffff';
      toast.style.padding = '10px 18px';
      toast.style.borderRadius = '999px';
      toast.style.fontSize = '13px';
      toast.style.fontWeight = '600';
      toast.style.fontFamily = "'Plus Jakarta Sans', system-ui, -apple-system, sans-serif";
      toast.style.boxShadow = '0 8px 24px rgba(0, 0, 0, 0.25)';
      toast.style.border = '1px solid rgba(255, 255, 255, 0.2)';
      toast.style.zIndex = '999999';
      toast.style.display = 'flex';
      toast.style.alignItems = 'center';
      toast.style.gap = '8px';
      toast.style.pointerEvents = 'none';
      toast.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(12px)';
      document.body.appendChild(toast);
    }

    toast.innerHTML = '<span style="color:#fbbf24;font-size:16px;">⚡</span> CSS Hot Reload: <span style="text-decoration:underline;">' + fileName + '</span>';
    toast.style.opacity = '1';
    toast.style.transform = 'translateY(0)';

    clearTimeout(toast._fadeTimeout);
    toast._fadeTimeout = setTimeout(function () {
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(12px)';
    }, 2200);
  }

  // Swap stylesheet di DOM tanpa reload halaman
  function reloadStylesheet(fileName, newMtime) {
    var links = Array.prototype.slice.call(document.querySelectorAll('link[rel="stylesheet"]'));
    var found = false;

    links.forEach(function (link) {
      var rawHref = link.getAttribute('href') || '';
      if (rawHref.indexOf(fileName) !== -1) {
        found = true;
        var newLink = document.createElement('link');
        newLink.rel = 'stylesheet';

        var newHref;
        try {
          var url = new URL(link.href, window.location.origin);
          url.searchParams.set('v', newMtime || Date.now());
          newHref = url.toString();
        } catch (e) {
          var cleanHref = link.href.split('?')[0];
          newHref = cleanHref + '?v=' + (newMtime || Date.now());
        }

        newLink.href = newHref;

        // Pertahankan media & crossorigin jika ada
        if (link.media) newLink.media = link.media;

        // Begitu stylesheet baru selesai di-parse browser, hapus stylesheet lama
        newLink.onload = function () {
          setTimeout(function () {
            if (link.parentNode) {
              link.parentNode.removeChild(link);
            }
          }, 60);
          showToast(fileName);
        };

        newLink.onerror = function () {
          console.warn('[CabilRent CSS Reload] Gagal memuat:', newHref);
        };

        // Sisipkan link baru tepat setelah link lama untuk transisi mulus (no FOUC)
        if (link.parentNode) {
          link.parentNode.insertBefore(newLink, link.nextSibling);
        } else {
          document.head.appendChild(newLink);
        }
      }
    });

    if (found) {
      console.log('%c[CabilRent CSS Hot Reload]%c ' + fileName + ' berhasil diperbarui di DOM.', 'color:#fbbf24;font-weight:bold;', 'color:inherit;');
    }
  }

  function checkChanges() {
    // Jangan polling jika tab sedang tidak aktif / tersembunyi
    if (document.hidden || isPolling || consecutiveErrors >= 3) return;
    isPolling = true;

    var xhr = new XMLHttpRequest();
    xhr.open('GET', endpoint, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.timeout = 3000;

    xhr.onload = function () {
      isPolling = false;
      if (xhr.status === 200) {
        consecutiveErrors = 0;
        try {
          var data = JSON.parse(xhr.responseText);
          if (data && data.files) {
            if (lastKnownTimestamps === null) {
              lastKnownTimestamps = data.files;
            } else {
              for (var file in data.files) {
                if (Object.prototype.hasOwnProperty.call(data.files, file)) {
                  var currentMtime = data.files[file];
                  var oldMtime = lastKnownTimestamps[file];
                  if (oldMtime !== undefined && currentMtime > oldMtime) {
                    reloadStylesheet(file, currentMtime);
                  }
                }
              }
              lastKnownTimestamps = data.files;
            }
          }
        } catch (e) {
          // JSON parse error, ignore
        }
      } else {
        consecutiveErrors++;
        if (consecutiveErrors >= 3) {
          clearInterval(timerId);
          console.warn('[CabilRent CSS Reload] Endpoint nonaktif, polling dihentikan otomatis.');
        }
      }
    };

    xhr.onerror = function () {
      isPolling = false;
      consecutiveErrors++;
    };

    xhr.ontimeout = function () {
      isPolling = false;
    };

    xhr.send();
  }

  function startWatcher() {
    if (timerId) clearInterval(timerId);
    if (!document.hidden) {
      checkChanges();
      timerId = setInterval(checkChanges, pollIntervalMs);
    }
  }

  // Hentikan total polling saat tab tidak aktif untuk menghemat resource CPU & web server
  document.addEventListener('visibilitychange', function () {
    if (document.hidden) {
      if (timerId) clearInterval(timerId);
    } else {
      consecutiveErrors = 0;
      startWatcher();
    }
  });

  window.addEventListener('focus', function () {
    if (!document.hidden && !timerId) {
      consecutiveErrors = 0;
      startWatcher();
    }
  });

  window.addEventListener('blur', function () {
    // Kurangi polling saat window blur
    if (timerId) clearInterval(timerId);
  });

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', startWatcher);
  } else {
    startWatcher();
  }

  console.log('%c[CabilRent]%c CSS Hot Reload aktif (Monitoring public/css/*.css)', 'color:#22c55e;font-weight:bold;', 'color:inherit;');
})();
