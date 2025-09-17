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
    /* ==================== Base ==================== */
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

/* ==================== Sidebar ==================== */
#sidebar {
  position: fixed;
  top: 0;
  bottom: 0; /* s’assurer qu’il prend toute la hauteur */
  width: 220px;
  background-color: #003566;
  overflow-y: auto;
}


body.ltr #sidebar { left: 0; right: auto; }
body.rtl #sidebar { right: 0; left: auto; }

#sidebar.compact { width: 80px; }

/* ==================== Main content ==================== */
.main-content {
  transition: margin 0.3s;
  min-height: 100vh;
  padding: 1rem;
}

body.ltr .main-content { margin-left: 220px; }
body.ltr .main-content.compact { margin-left: 80px; }

body.rtl .main-content { margin-right: 220px; }
body.rtl .main-content.compact { margin-right: 80px; }

/* ==================== Nav links ==================== */
#sidebar .nav-link {
  color: white;
  display: flex;
  align-items: center;
  padding: 0.5rem 1rem;
  white-space: nowrap;
  transition: background-color 0.2s;
  width: 100%;
}

#sidebar .nav-link:hover {
  background-color: #dee2e6;
  color: #003566 !important;
}

#sidebar.compact .nav-link {
      justify-content: center !important; /* icônes centrées toujours */

  padding-left: 0;
  padding-right: 0;
}

#sidebar.compact .nav-link .label {
  display: none !important; /* texte masqué en compact */
}

/* Icônes */
#sidebar .nav-link i {
  font-size: 2.3rem;
  min-width: 34px;
  height: 24px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  margin: 0;
}
/*
#sidebar .nav-link i {
  display: flex !important;
  align-items: center;
  justify-content: center;
  margin: 0;
  width: 24px;
  height: 24px;
} */
#sidebar ul.nav {
  padding-left: 0 !important;
  padding-right: 0 !important;
}
/* ==================== Sidebar header ==================== */
.sidebar-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem;
}

#sidebar.compact .sidebar-header {
  justify-content: center;
}

.brand-text {
  color: white;
  font-weight: bold;
  font-size: 1.2rem;
}

.brand-text1 {
  color: white;
  font-size: 0.9rem;
}

#sidebar.compact .brand-text {
  display: none !important;
}

/* ==================== User section ==================== */
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

#sidebar.compact .user-section {
  justify-content: center;
}

#sidebar.compact .user-section .username {
  display: none !important;
}

/* ==================== Toggle button ==================== */
#toggleBtn {
  background-color: transparent;
  border: none;
  color: white;
}

/* ==================== Active link ==================== */
#sidebar .nav-link.active {
  background-color: #dee2e6;
  color: #003566 !important;
  font-weight: 600;
}

/* ==================== Dropdown ==================== */
.dropdown-toggle::after { display: none; }

#sidebar .dropdown-menu {
  z-index: 9999;
}
/* !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */

 /* langue style */
 /* Style switch langues */
.lang-switch .nav-link {
    border-radius: 8px;
    transition: background-color 0.2s;
}

.lang-switch .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.15);
}

.lang-switch .active {
    background-color: rgba(255, 255, 255, 0.25);
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
