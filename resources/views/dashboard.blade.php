<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

  <style>
    #sidebar {
      width: 220px;
      background-color: #003566;
      transition: width 0.3s;
    }

    #sidebar.compact {
      width: 70px;
    }

    .main-content {
      transition: margin-left 0.3s;
      margin-left: 220px;
    }

    .main-content.compact {
      margin-left: 70px;
    }

    #sidebar .nav-link {
      color: white;
    }

    #sidebar .nav-link:hover {
      background-color: #dee2e6;
      color: #003566;
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

    #sidebar .nav-link {
      display: flex;
      align-items: center;
      padding: 0.5rem 1rem;
    }

    #sidebar.compact .nav-link {
      justify-content: center;
      padding: 0.5rem;
    }

    #toggleBtn {
      background-color: #ffffff;
      border: none;
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
    }

    .dropdown-toggle::after {
      display: none;
    }
  </style>
</head>
<body>

<div class="d-flex">
  <nav id="sidebar" class="border-end vh-100">
    <div class="d-flex flex-column p-3 h-100">
      <!-- Header with title and toggle -->
      <div class="sidebar-header">
        <span class="fs-5 fw-bold text-white brand-text">TraduOffice</span>
        <button id="toggleBtn" class="btn btn-sm" title="Toggle sidebar">
          <i class="bi bi-list"></i>
        </button>
      </div>

      <!-- Nav links -->
      <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-1">
          <a href="#" class="nav-link">
            <i class="bi bi-house-door-fill fs-5"></i>
            <span class="label ms-2">Accueil</span>
          </a>
        </li>
        <li class="nav-item mb-1">
          <a href="#" class="nav-link">
            <i class="bi bi-plus-circle fs-5"></i>
            <span class="label ms-2">Nouvelle Demande</span>
          </a>
        </li>
        <li class="nav-item mb-1">
          <a href="#" class="nav-link">
            <i class="bi bi-clock-history fs-5"></i>
            <span class="label ms-2">Suivi Demande</span>
          </a>
        </li>
        <li class="nav-item mb-1">
          <a href="#" class="nav-link">
            <i class="bi bi-file-earmark-text fs-5"></i>
            <span class="label ms-2">Fichier Traduit</span>
          </a>
        </li>
        <li class="nav-item mb-1">
          <a href="#" class="nav-link">
            <i class="bi bi-archive fs-5"></i>
            <span class="label ms-2">Archives</span>
          </a>
        </li>
      </ul>

      <hr class="border-light" />

      <!-- User dropdown -->
      <div class="dropdown mt-auto">
        <a href="#" class="user-section dropdown-toggle text-decoration-none" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="https://i.pravatar.cc/40" alt="user" width="32" height="32" class="rounded-circle" />
          <span class="label ms-2 brand-text">Utilisateur</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
          <li><a class="dropdown-item" href="#">Profil</a></li>
          <li><a class="dropdown-item" href="#">Paramètres</a></li>
          <li><hr class="dropdown-divider" /></li>
          <li><a class="dropdown-item" href="#">Déconnexion</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <main class="main-content p-4">
    <h1>Dashboard</h1>
    <p>Bienvenue sur votre interface TraduOffice.</p>
  </main>
</div>

<!-- Bootstrap & JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const sidebar = document.getElementById('sidebar');
  const toggleBtn = document.getElementById('toggleBtn');
  const main = document.querySelector('.main-content');

  toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('compact');
    main.classList.toggle('compact');
  });
</script>

</body>
</html>
