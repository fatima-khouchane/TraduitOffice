<div class="d-flex flex-column p-3 h-100 text-white">
    <!-- Titre + bouton toggle -->
    <div class="sidebar-header">
      <span class="fs-5 fw-bold brand-text">TraduitOffice</span>
      <button id="toggleBtn" class="btn btn-sm">
        <i class="bi bi-list"></i>
      </button>
    </div>

    <!-- Liens navigation -->
   <ul class="nav nav-pills flex-column mb-auto">

    @if(Auth::user()->role === 'admin')
    <!-- 1. Nouvelle Demande -->
    <li class="nav-item mb-1">
        <a href="{{ route('demande.create') }}"
           class="nav-link text-white {{ request()->routeIs('demande.create') ? 'active' : '' }}">
            <i class="bi bi-plus-circle fs-5"></i>
            <span class="label ms-2">Nouvelle Demande</span>
        </a>
    </li>
    @endif

    <!-- 2. Suivi Demande -->
    <li class="nav-item mb-1">
        <a href="{{ route('suivi_demande.index') }}"
           class="nav-link text-white {{ request()->routeIs('suivi_demande.index') ? 'active' : '' }}">
            <i class="bi bi-clock-history fs-5"></i>
            <span class="label ms-2">Suivi Demande</span>
        </a>
    </li>

    <!-- 3. Fichier Traduit -->
    <li class="nav-item mb-1">
        <a href="{{ route('suivi_demande.index2') }}"
           class="nav-link text-white {{ request()->routeIs('suivi_demande.index2') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text fs-5"></i>
            <span class="label ms-2">Fichier Traduit</span>
        </a>
    </li>

    @if(Auth::user()->role === 'admin')
    <!-- 4. Ajouter un traducteur -->
    <li class="nav-item mb-1">
        <a href="{{ route('admin.translators.create') }}"
           class="nav-link text-white {{ request()->routeIs('admin.translators.create') ? 'active' : '' }}">
            <i class="bi bi-person-plus fs-5"></i>
            <span class="label ms-2">Ajouter un traducteur</span>
        </a>
    </li>

    <!-- 5. Liste des traducteurs -->
    <li class="nav-item mb-1">
        <a href="{{ route('translator.index') }}"
           class="nav-link text-white {{ request()->routeIs('translator.index') ? 'active' : '' }}">
            <i class="bi bi-people fs-5"></i>
            <span class="label ms-2">Liste des traducteurs</span>
        </a>
    </li>

    <!-- 3. Demandes Confirmées -->
<li class="nav-item mb-1">
    <a href="{{ route('admin.demandes.confirmees') }}"
       class="nav-link text-white {{ request()->routeIs('admin.demandes.confirmees') ? 'active' : '' }}">
        <i class="bi bi-check2-circle fs-5"></i>
        <span class="label ms-2">Demandes Confirmées</span>
    </a>
</li>

    <!-- 6. Statistique -->
    <li class="nav-item mb-1">
        <a href="{{ route('statistique') }}"
           class="nav-link text-white {{ request()->routeIs('statistique') ? 'active' : '' }}">
            <i class="bi bi-graph-up fs-5"></i>
            <span class="label ms-2">Statistique</span>
        </a>
    </li>
    @endif


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
