// Autocomplete kategori pada navbar dan navigasi langsung ke katalog
document.addEventListener('DOMContentLoaded', function () {
  var searchForms = document.querySelectorAll('.nav-search');
  if (!searchForms.length) return;

  var kategoriBawaan = [
    { nama: 'Kebaya', slug: 'kebaya' },
    { nama: 'Kamisol', slug: 'kamisol' },
    { nama: 'Heels', slug: 'heels' },
    { nama: 'Paket Kebaya', slug: 'kebaya' }
  ];

  // Ambil kategori dari cache sessionStorage atau database jika belum ada
  function perbaruiKategoriDariData(data) {
    if (Array.isArray(data) && data.length) {
      data.forEach(function (cat) {
        var nama = (cat.nama_kategori || '').replace(/kemben/i, 'Kamisol').trim();
        var slug = cat.slug || nama.toLowerCase().replace(/\s+/g, '-');
        var sudahAda = kategoriBawaan.some(function (k) {
          return k.nama.toLowerCase() === nama.toLowerCase();
        });
        if (!sudahAda && nama) {
          kategoriBawaan.push({ nama: nama, slug: slug });
        }
      });
    }
  }

  try {
    var cached = sessionStorage.getItem('cabil_kategori_list');
    if (cached) {
      perbaruiKategoriDariData(JSON.parse(cached));
    } else {
      fetch('/api/kategori')
        .then(function (res) { return res.ok ? res.json() : []; })
        .then(function (data) {
          if (Array.isArray(data) && data.length) {
            sessionStorage.setItem('cabil_kategori_list', JSON.stringify(data));
            perbaruiKategoriDariData(data);
          }
        })
        .catch(function () {});
    }
  } catch (err) {
    // sessionStorage dinonaktifkan browser
  }

  function scrollKeKatalog() {
    var target = document.getElementById('semua-koleksi');
    if (target) {
      setTimeout(function () {
        target.scrollIntoView({ behavior: 'smooth' });
      }, 100);
    }
  }

  searchForms.forEach(function (form) {
    var input = form.querySelector('input[name="q"]');
    if (!input) return;

    // Pastikan posisi relatif pada form untuk dropdown
    form.style.position = 'relative';

    var dropdown = document.createElement('div');
    dropdown.className = 'nav-search-dropdown';
    dropdown.setAttribute('role', 'listbox');
    form.appendChild(dropdown);

    var itemTerpilih = -1;

    function cariKategori(huruf) {
      var q = (huruf || '').trim().toLowerCase();
      if (!q) return [];

      var hasil = [];
      var seen = {};

      kategoriBawaan.forEach(function (cat) {
        var nama = cat.nama.trim();
        var namaLower = nama.toLowerCase();
        var kataList = namaLower.split(/\s+/);

        // Kategori yang berawalan huruf/kata kunci tersebut
        var cocok = namaLower.indexOf(q) === 0 || kataList.some(function (kata) {
          return kata.indexOf(q) === 0;
        });

        if (cocok && !seen[cat.nama]) {
          seen[cat.nama] = true;
          hasil.push(cat);
        }
      });

      return hasil;
    }

    function sorotTeks(teks, kata) {
      if (!kata) return teks;
      var regex = new RegExp('(' + kata.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
      return teks.replace(regex, '<strong>$1</strong>');
    }

    function renderDropdown(cocokList, q) {
      dropdown.innerHTML = '';
      itemTerpilih = -1;

      if (!cocokList.length) {
        dropdown.classList.remove('is-visible');
        return;
      }

      var header = document.createElement('div');
      header.className = 'nav-search-header';
      header.textContent = 'Kategori';
      dropdown.appendChild(header);

      cocokList.forEach(function (item, idx) {
        var link = document.createElement('a');
        link.className = 'nav-search-item';
        link.setAttribute('role', 'option');
        link.href = '/koleksi?kategori=' + encodeURIComponent(item.slug) + '#semua-koleksi';
        link.dataset.index = idx;
        link.dataset.slug = item.slug;
        link.dataset.nama = item.nama;

        link.innerHTML =
          '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">' +
          '<path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>' +
          '<line x1="7" y1="7" x2="7.01" y2="7"/>' +
          '</svg>' +
          '<span>' + sorotTeks(item.nama, q) + '</span>';

        link.addEventListener('click', function (e) {
          if (window.location.pathname.indexOf('/koleksi') !== -1) {
            e.preventDefault();
            tutupDropdown();
            input.value = '';
            var chip = document.querySelector('.chip[data-filter="' + item.slug + '"]');
            if (chip) {
              chip.click();
            } else if (typeof terapkanFilter === 'function') {
              terapkanFilter(item.slug, '');
            }
            scrollKeKatalog();
            history.replaceState(null, '', '/koleksi?kategori=' + encodeURIComponent(item.slug) + '#semua-koleksi');
          }
        });

        dropdown.appendChild(link);
      });

      dropdown.classList.add('is-visible');
    }

    function tutupDropdown() {
      dropdown.classList.remove('is-visible');
      itemTerpilih = -1;
    }

    // Munculkan saran saat mengetik (mulai dari 1 huruf)
    input.addEventListener('input', function () {
      var q = input.value.trim();
      if (q.length >= 1) {
        var cocok = cariKategori(q);
        renderDropdown(cocok, q);
      } else {
        tutupDropdown();
      }
    });

    input.addEventListener('keydown', function (e) {
      var items = dropdown.querySelectorAll('.nav-search-item');

      if (e.key === 'ArrowDown') {
        if (items.length) {
          e.preventDefault();
          itemTerpilih = (itemTerpilih + 1) % items.length;
          items.forEach(function (el, i) {
            el.classList.toggle('is-active', i === itemTerpilih);
          });
        }
      } else if (e.key === 'ArrowUp') {
        if (items.length) {
          e.preventDefault();
          itemTerpilih = (itemTerpilih - 1 + items.length) % items.length;
          items.forEach(function (el, i) {
            el.classList.toggle('is-active', i === itemTerpilih);
          });
        }
      } else if (e.key === 'Escape') {
        tutupDropdown();
      } else if (e.key === 'Enter') {
        if (itemTerpilih >= 0 && items[itemTerpilih]) {
          e.preventDefault();
          items[itemTerpilih].click();
        } else {
          e.preventDefault();
          tutupDropdown();
          var kata = input.value.trim();
          var tujuan = '/koleksi' + (kata ? ('?q=' + encodeURIComponent(kata)) : '') + '#semua-koleksi';

          if (window.location.pathname.indexOf('/koleksi') !== -1) {
            history.replaceState(null, '', tujuan);
            scrollKeKatalog();
            if (typeof terapkanFilter === 'function') {
              var aktif = document.querySelector('.chip.active');
              terapkanFilter(aktif ? aktif.dataset.filter : 'semua', kata);
            }
          } else {
            window.location.href = tujuan;
          }
        }
      }
    });

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      tutupDropdown();
      var kata = input.value.trim();
      var tujuan = '/koleksi' + (kata ? ('?q=' + encodeURIComponent(kata)) : '') + '#semua-koleksi';

      if (window.location.pathname.indexOf('/koleksi') !== -1) {
        history.replaceState(null, '', tujuan);
        scrollKeKatalog();
        if (typeof terapkanFilter === 'function') {
          var aktif = document.querySelector('.chip.active');
          terapkanFilter(aktif ? aktif.dataset.filter : 'semua', kata);
        }
      } else {
        window.location.href = tujuan;
      }
    });

    document.addEventListener('click', function (e) {
      if (!form.contains(e.target)) {
        tutupDropdown();
      }
    });
  });

  // Saat membuka halaman koleksi langsung scroll ke katalog jika ada pencarian atau anchor
  if (window.location.pathname.indexOf('/koleksi') !== -1) {
    var params = new URLSearchParams(window.location.search);
    var adaQuery = params.has('q') || params.has('search') || params.has('cari') || (params.has('kategori') && params.get('kategori') !== 'semua');
    var adaHash = window.location.hash === '#semua-koleksi';

    if (adaQuery || adaHash) {
      scrollKeKatalog();
    }
  }
});
