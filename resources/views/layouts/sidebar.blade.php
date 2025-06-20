<div class="d-flex flex-column p-3 h-100 text-white">
    <!-- Titre + bouton toggle -->
    <div class="sidebar-header">
      <span class="fs-5 fw-bold brand-text">TraduOffice</span>
      <button id="toggleBtn" class="btn btn-sm">
        <i class="bi bi-list"></i>
      </button>
    </div>

    <!-- Liens navigation -->
    <ul class="nav nav-pills flex-column mb-auto">
      <li class="nav-item mb-1">
        <a href="{{ route('dashboard') }}" class="nav-link text-white">
          <i class="bi bi-house-door-fill fs-5"></i>
          <span class="label ms-2">Accueil</span>
        </a>
      </li>
      <li class="nav-item mb-1">
        <a href="{{ route('demande.create') }}" class="nav-link text-white">
          <i class="bi bi-plus-circle fs-5"></i>
          <span class="label ms-2">Nouvelle Demande</span>
        </a>
      </li>
      <li class="nav-item mb-1">
        <a href="#" class="nav-link text-white">
          <i class="bi bi-clock-history fs-5"></i>
          <span class="label ms-2">Suivi Demande</span>
        </a>
      </li>
      <li class="nav-item mb-1">
        <a href="#" class="nav-link text-white">
          <i class="bi bi-file-earmark-text fs-5"></i>
          <span class="label ms-2">Fichier Traduit</span>
        </a>
      </li>
      <li class="nav-item mb-1">
        <a href="#" class="nav-link text-white">
          <i class="bi bi-archive fs-5"></i>
          <span class="label ms-2">Archives</span>
        </a>
      </li>
    </ul>

    <hr class="border-light" />

    <!-- Dropdown utilisateur -->
    <div class="dropdown mt-auto">
      <a href="#" class="user-section dropdown-toggle text-decoration-none" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="https://i.pravatar.cc/40" alt="user" width="32" height="32" class="rounded-circle" />
        <span class="ms-2 label brand-text">Utilisateur</span>
      </a>
      <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="userDropdown">
        <li><a class="dropdown-item" href="#">Profil</a></li>
        <li><a class="dropdown-item" href="#">Paramètres</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="#">Déconnexion</a></li>
      </ul>
    </div>
  </div>
