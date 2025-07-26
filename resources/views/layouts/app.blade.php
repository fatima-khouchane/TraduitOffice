<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" @if(app()->getLocale() === 'ar') dir="rtl" @endif>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'Mon App')</title>

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

  <!-- Fonts Arabe -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo&family=Noto+Kufi+Arabic:wght@400;600&family=Noto+Sans+Arabic:wght@400;700&display=swap" rel="stylesheet">

  @php $isArabic = app()->getLocale() === 'ar'; @endphp

  <style>
    body {
      margin: 0;
      font-size: 15px;
    }

    body.rtl {
      font-family: 'Noto Sans Arabic', 'Cairo', 'Noto Kufi Arabic', sans-serif;
      direction: rtl;
      text-align: right;
    }

    body.ltr {
      font-family: 'Poppins', sans-serif;
      direction: ltr;
      text-align: left;
    }

    /* Sidebar */
    #sidebar {
      position: fixed;
      top: 0;
      height: 100vh;
      width: 220px;
      background-color: #003566;
      transition: width 0.3s;
      overflow-x: hidden;
      overflow-y: auto;
      z-index: 1000;
    }

    body.ltr #sidebar { left: 0; right: auto; }
    body.rtl #sidebar { right: 0; left: auto; }

    #sidebar.compact { width: 70px; }

    /* Main content */
    .main-content {
      transition: margin 0.3s;
      min-height: 100vh;
      padding: 1rem;
    }

    body.ltr .main-content { margin-left: 220px; }
    body.ltr .main-content.compact { margin-left: 70px; }

    body.rtl .main-content { margin-right: 220px; }
    body.rtl .main-content.compact { margin-right: 70px; }

    /* Nav links */
    #sidebar .nav-link {
      color: white;
      display: flex;
      align-items: center;
      padding: 0.5rem 1rem;
      white-space: nowrap;
      transition: background-color 0.2s;
      width: 100%;
    }

    body.ltr #sidebar .nav-link {
      flex-direction: row;
      justify-content: flex-start;
    }

    body.rtl #sidebar .nav-link {
      flex-direction: row-reverse;
      justify-content: flex-start;
    }

    #sidebar .nav-link:hover {
      background-color: #dee2e6;
      color: #003566 !important;
    }

    #sidebar.compact .nav-link {
      justify-content: center !important;
      padding-left: 0;
      padding-right: 0;
    }

    #sidebar.compact .nav-link .label {
      display: none !important;
    }

    #sidebar .nav-link i {
      font-size: 1.3rem;
      min-width: 24px;
      text-align: center;
    }

    body.ltr #sidebar .nav-link i {
      margin-right: 0.5rem;
      margin-left: 0;
    }

    body.rtl #sidebar .nav-link i {
      margin-left: 0.5rem;
      margin-right: 0;
    }

    #sidebar.compact .nav-link i {
      margin: 0 auto !important;
    }

    /* Compact: hide text but keep icon centered */
    #sidebar.compact .brand-text,
    #sidebar.compact .user-section .username {
      display: none !important;
    }

    .sidebar-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 1rem;
    }

    .user-section {
      display: flex;
      align-items: center;
      padding: 0.5rem 1rem;
      color: white;
    }

    .user-section .username {
      margin-left: 0.5rem;
      margin-right: 0.5rem;
    }

    #toggleBtn {
      background-color: transparent;
      border: none;
      color: white;
    }

    #sidebar .nav-link.active {
      background-color: #dee2e6;
      color: #003566 !important;
      font-weight: 600;
    }

    .brand-text {
      color: white;
      font-weight: bold;
      font-size: 1.2rem;
    }

    .dropdown-toggle::after {
      display: none;
    }

    #sidebar .dropdown-menu {
      z-index: 9999;
    }
  </style>
</head>

<body class="{{ $isArabic ? 'rtl' : 'ltr' }}">
  <div id="sidebar" class="d-flex flex-column {{ session('sidebar-compact') ? 'compact' : '' }}">
    @include('layouts.sidebar')
  </div>

  <main class="main-content {{ session('sidebar-compact') ? 'compact' : '' }}">
    @yield('content')
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleBtn');
    const mainContent = document.querySelector('.main-content');

    if (localStorage.getItem('sidebar-compact') === 'true') {
      sidebar.classList.add('compact');
      mainContent.classList.add('compact');
    }

    toggleBtn?.addEventListener('click', () => {
      sidebar.classList.toggle('compact');
      mainContent.classList.toggle('compact');
      localStorage.setItem('sidebar-compact', sidebar.classList.contains('compact'));
    });
  </script>
</body>
</html>
