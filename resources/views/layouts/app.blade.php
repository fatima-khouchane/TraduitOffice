<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'Mon App')</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }

    #sidebar {
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  width: 220px;
  background-color: #003566;
  transition: width 0.3s;
  overflow: hidden;
  z-index: 1000;
}

#sidebar.compact {
  width: 70px;
}

.main-content {
  margin-left: 220px;
  transition: margin-left 0.3s;
}

.main-content.compact {
  margin-left: 70px;
}


    #sidebar .nav-link {
      color: white;
      display: flex;
      align-items: center;
      padding: 0.5rem 1rem;
      white-space: nowrap;
      transition: background-color 0.2s;
    }

    #sidebar .nav-link:hover {
      background-color: #dee2e6;
      color: #003566 !important;
    }

    #sidebar.compact .nav-link {
      justify-content: center;
      padding-left: 0.5rem;
      padding-right: 0.5rem;
    }

    #sidebar.compact .nav-link:hover {
      background-color: rgba(255, 255, 255, 0.2);
      width: 100%;
    }

    #sidebar .nav-link .label,
    #sidebar .brand-text {
      transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    #sidebar.compact .nav-link .label,
    #sidebar.compact .brand-text {
      opacity: 0;
      visibility: hidden;
      width: 0;
      overflow: hidden;
    }

    #toggleBtn {
      background-color: transparent;
      border: none;
      color: white;
    }

    .sidebar-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 1rem;
    }

    .user-section {
      display: flex;
      align-items: center;
      color: white;
      padding: 0.5rem 1rem;
      white-space: nowrap;
    }

    #sidebar.compact .user-section {
      justify-content: center;
      padding-left: 0.5rem;
      padding-right: 0.5rem;
    }

    .dropdown-toggle::after {
      display: none;
    }

    .border-light {
      border-color: rgba(255, 255, 255, 0.2) !important;
    }

    #sidebar .dropdown-menu {
      z-index: 9999;
    }
  </style>
</head>
<body>
  <div class="d-flex">
    <nav id="sidebar" class="vh-100 border-end d-flex flex-column">
      @include('layouts.sidebar')
    </nav>

    <main class="main-content p-4">
      @yield('content')
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleBtn');
    const mainContent = document.querySelector('.main-content');

    toggleBtn?.addEventListener('click', () => {
      sidebar.classList.toggle('compact');
      mainContent.classList.toggle('compact');
    });
  </script>
</body>
</html>
