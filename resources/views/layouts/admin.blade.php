<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin Panel - Cabil.Rent')</title>

  <!-- Google Fonts & Admin CSS -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset_versioned('css/dashboard.css') }}">
  <link rel="stylesheet" href="{{ asset_versioned('css/transisi.css') }}">
  @yield('styles')
</head>
<body>

  <div class="app-container" id="app">
    @include('layouts.partials.admin-sidebar')

    <div class="main-wrapper">
      @yield('header')

      <main class="main-content">
        @yield('content')
      </main>
    </div>
  </div>

  @include('layouts.partials.admin-logout-modal')

  <script src="{{ asset('js/dashboard.js') }}"></script>
  <script src="{{ asset('js/transisi.js') }}"></script>
  @yield('scripts')
</body>
</html>
