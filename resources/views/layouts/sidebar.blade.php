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
      {{-- <li class="nav-item mb-1">
        <a href="{{ route('dashboard') }}"
           class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}">
          <i class="bi bi-house-door-fill fs-5"></i>
          <span class="label ms-2">Accueil</span>
        </a>
      </li> --}}
          @if(Auth::user()->role === 'admin')
      <li class="nav-item mb-1">
        <a href="{{ route('demande.create') }}"
           class="nav-link text-white {{ request()->routeIs('demande.create') ? 'active' : '' }}">
          <i class="bi bi-plus-circle fs-5"></i>
          <span class="label ms-2">Nouvelle Demande</span>
        </a>
      </li>
      <li class="nav-item mb-1">
        <a href="{{ route('suivi_demande.index') }}"
           class="nav-link text-white {{ request()->routeIs('suivi_demande.index') ? 'active' : '' }}">
          <i class="bi bi-clock-history fs-5"></i>
          <span class="label ms-2">Suivi Demande</span>
        </a>
      </li>
      <li class="nav-item mb-1">
        <a href="{{ route('suivi_demande.index2') }}"
           class="nav-link text-white {{ request()->routeIs('suivi_demande.index2') ? 'active' : '' }}">
          <i class="bi bi-file-earmark-text fs-5"></i>
          <span class="label ms-2">Fichier Traduit</span>
        </a>
      </li>

      <li class="nav-item mb-1">
        <a href="{{ route('statistique') }}"
        class="nav-link text-white {{ request()->routeIs('statistique') ? 'active' : '' }}">
        <i class="bi bi-graph-up fs-5"></i>
        <span class="label ms-2">Statistique</span>

        </a>
      </li>

      <li class="nav-item mb-1">
  <a href="{{ route('admin.translators.create') }}"
     class="nav-link text-white {{ request()->routeIs('admin.translators.create') ? 'active' : '' }}">
    <i class="bi bi-person-plus fs-5"></i>
    <span class="label ms-2">Ajouter un traducteur</span>
  </a>
</li>
<li class="nav-item mb-1">
  <a href="{{ route('translator.index') }}"
     class="nav-link text-white {{ request()->routeIs('translator.index') ? 'active' : '' }}">
    <i class="bi bi-people fs-5"></i>
    <span class="label ms-2">Liste des traducteurs</span>
  </a>
</li>

    @elseif(Auth::user()->role === 'translator')
 <li class="nav-item mb-1">
        <a href="{{ route('translator.tasks') }}"
           class="nav-link text-white {{ request()->routeIs('translator.tasks') ? 'active' : '' }}">
          <i class="bi bi-journal-text fs-5"></i>
          <span class="label ms-2">Mes Traductions</span>
        </a>
      </li>
    @endif
        </a>
      </li>
    </ul>

    <hr class="border-light" />

  <!-- Dropdown utilisateur -->
<div class="dropdown mt-auto">
    <a href="#" class="user-section dropdown-toggle text-decoration-none d-flex align-items-center" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <span class="ms-2 label brand-text">{{ Auth::user()->name }}</span>
        <i class="ms-2 bi bi-caret-down-fill"></i>
    </a>

    <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="userDropdown">
        <li><a class="dropdown-item" href="{{ route('profil.edit') }}">Profil</a></li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="dropdown-item" type="submit">Déconnexion</button>
            </form>
        </li>
    </ul>
</div>
