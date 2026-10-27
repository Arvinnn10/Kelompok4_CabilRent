<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Cabil.Rent</title>
  
  <!-- Style khusus halaman login/register -->
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  
  <!-- Font Google -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    /* Penyesuaian scroll jika form register lebih panjang */
    .register-content {
      overflow-y: auto;
      max-height: 100vh;
    }
    .register-card {
      margin: auto;
    }
  </style>
</head>
<body class="login-body">

  <div class="login-container">
    <!-- Banner sebelah kiri  -->
    <div class="login-banner">
      <img src="{{ asset('images/login-banner.png') }}" alt="cabil.rent Illustration" class="login-banner-img">
      
      <!-- Teks pembuka di atas gambar banner -->
      <div class="login-banner-overlay">
        <h1 class="banner-title">Join Us !</h1>
        <p class="banner-subtitle">Daftarkan akun Anda sekarang</p>
      </div>
    </div>

    <!-- Form register di sebelah kanan -->
    <div class="login-content register-content">
      <div class="login-card register-card">
        <div class="login-card-header">
          <h2 class="login-title">Daftar Akun</h2>
          <p class="login-desc">Lengkapi data untuk membuat akun</p>
        </div>

        @if ($errors->any())
          <div style="background-color: #ffebee; color: #c62828; padding: 10px 14px; border-radius: 8px; font-size: 13px; margin-bottom: 16px; border: 1px solid #ffcdd2;">
            {{ $errors->first() }}
          </div>
        @endif

        <!-- Form register -->
        <form action="{{ route('register.post') }}" method="POST" class="login-form">
          @csrf

          <!-- Nama Lengkap -->
          <div class="form-group">
            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
            <div class="input-wrapper">
              <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-input" placeholder="Masukkan nama lengkap" value="{{ old('nama_lengkap') }}" required>
            </div>
          </div>

          <!-- Username -->
          <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <div class="input-wrapper">
              <input type="text" id="username" name="username" class="form-input" placeholder="Masukkan username" value="{{ old('username') }}" required>
            </div>
          </div>

          <!-- Email -->
          <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <div class="input-wrapper">
              <input type="email" id="email" name="email" class="form-input" placeholder="Masukkan email" value="{{ old('email') }}" required>
            </div>
          </div>

          <!-- No Telepon -->
          <div class="form-group">
            <label for="no_telp" class="form-label">No. Telepon / WhatsApp</label>
            <div class="input-wrapper">
              <input type="text" id="no_telp" name="no_telp" class="form-input" placeholder="08xxxxxxxxxx" value="{{ old('no_telp') }}" required>
            </div>
          </div>

          <!-- Password -->
          <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <div class="input-wrapper">
              <input type="password" id="password" name="password" class="form-input" placeholder="Minimal 4 karakter" required>
              <button type="button" class="password-toggle-btn" id="togglePasswordBtn" aria-label="Lihat Password">
                <!-- Ikon mata -->
                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                  <circle cx="12" cy="12" r="3"></circle>
                </svg>
              </button>
            </div>
          </div>

          <!-- Tombol daftar -->
          <button type="submit" class="btn-submit">Daftar Sekarang</button>

          <!-- Garis pemisah atau -->
          <div class="login-divider">
            <span>atau</span>
          </div>

          <!-- Tombol daftar dengan Google -->
          <a href="{{ route('auth.google') }}" class="btn-google">
            <svg class="google-icon" viewBox="0 0 24 24" width="18" height="18">
              <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
              <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
              <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
              <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
            </svg>
            Daftar dengan Google
          </a>
        </form>

        <p class="login-footer-text">
          Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
        </p>
      </div>
    </div>
  </div>

  <!-- Script toggle password -->
  <script>
    const toggleBtn = document.getElementById('togglePasswordBtn');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    toggleBtn.addEventListener('click', () => {
      const isPassword = passwordInput.getAttribute('type') === 'password';
      passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
      
      if (isPassword) {
        eyeIcon.innerHTML = `
          <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
          <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
          <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
          <line x1="2" y1="2" x2="22" y2="22"></line>
        `;
      } else {
        eyeIcon.innerHTML = `
          <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
          <circle cx="12" cy="12" r="3"></circle>
        `;
      }
    });
  </script>
</body>
</html>
