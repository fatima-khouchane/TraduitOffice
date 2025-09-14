<div class="d-flex flex-column p-3 h-100 text-white">
    <!-- Titre + bouton toggle -->
    <div class="sidebar-header d-flex justify-content-between align-items-center mb-3">
        <span class="fs-5 fw-bold brand-text">TraduitOffice</span>
        <button id="toggleBtn" class="btn btn-sm text-white">
            <i class="bi bi-list fs-5"></i>
        </button>
    </div>

    <!-- Liens navigation -->
    <ul class="nav nav-pills flex-column mb-auto">
        @if(Auth::user()->role === 'admin')
        <li class="nav-item mb-1">
            <a href="{{ route('demande.create') }}"
               class="nav-link text-white {{ request()->routeIs('demande.create') ? 'active' : '' }}">
                <i class="bi bi-plus-circle fs-5"></i>
                <span class="label ms-2">{{ __('sidebar.nouvelle_demande') }}</span>
            </a>
        </li>
        @endif

        <li class="nav-item mb-1">
            <a href="{{ route('suivi_demande.index') }}"
               class="nav-link text-white {{ request()->routeIs('suivi_demande.index') ? 'active' : '' }}">
                <i class="bi bi-clock-history fs-5"></i>
                <span class="label ms-2">{{ __('sidebar.suivi_demande') }}</span>
            </a>
        </li>

        <li class="nav-item mb-1">
            <a href="{{ route('suivi_demande.index2') }}"
               class="nav-link text-white {{ request()->routeIs('suivi_demande.index2') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text fs-5"></i>
                <span class="label ms-2">{{ __('sidebar.fichier_traduit') }}</span>
            </a>
        </li>

        @if(Auth::user()->role === 'admin')
        <li class="nav-item mb-1">
            <a href="{{ route('admin.translators.create') }}"
               class="nav-link text-white {{ request()->routeIs('admin.translators.create') ? 'active' : '' }}">
                <i class="bi bi-person-plus fs-5"></i>
                <span class="label ms-2">{{ __('sidebar.ajouter_traducteur') }}</span>
            </a>
        </li>

        <li class="nav-item mb-1">
            <a href="{{ route('translator.index') }}"
               class="nav-link text-white {{ request()->routeIs('translator.index') ? 'active' : '' }}">
                <i class="bi bi-people fs-5"></i>
                <span class="label ms-2">{{ __('sidebar.liste_traducteurs') }}</span>
            </a>
        </li>

        <li class="nav-item mb-1">
            <a href="{{ route('admin.demandes.confirmees') }}"
               class="nav-link text-white {{ request()->routeIs('admin.demandes.confirmees') ? 'active' : '' }}">
                <i class="bi bi-check2-circle fs-5"></i>
                <span class="label ms-2">{{ __('sidebar.demandes_confirmees') }}</span>
            </a>
        </li>

        <li class="nav-item mb-1">
            <a href="{{ route('statistique') }}"
               class="nav-link text-white {{ request()->routeIs('statistique') ? 'active' : '' }}">
                <i class="bi bi-graph-up fs-5"></i>
                <span class="label ms-2">{{ __('sidebar.statistique') }}</span>
            </a>
        </li>
        @endif
    </ul>
<div class="dropdown mt-2 mb-3">
    <a href="#" class="user-section dropdown-toggle text-decoration-none d-flex align-items-center"
       id="langDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-translate fs-5"></i>
        <span class="ms-2 label brand-text brand-text1">
            @if(app()->getLocale() == 'fr') Français
            @elseif(app()->getLocale() == 'en') English
            @elseif(app()->getLocale() == 'ar') العربية
            @endif
        </span>
        <i class="ms-2 bi bi-caret-down-fill"></i>
    </a>

    <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="langDropdown">
        <li><a class="dropdown-item {{ app()->getLocale() == 'fr' ? 'active' : '' }}" href="{{ route('lang.switch', 'fr') }}">Français</a></li>
        <li><a class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}" href="{{ route('lang.switch', 'en') }}">English</a></li>
        <li><a class="dropdown-item {{ app()->getLocale() == 'ar' ? 'active' : '' }}" href="{{ route('lang.switch', 'ar') }}">العربية</a></li>
    </ul>
</div>
<hr class="border-light" />

<!-- Dropdown langues -->


    <!-- Dropdown utilisateur -->
    <div class="dropdown mt-auto">
        <a href="#" class="user-section dropdown-toggle text-decoration-none d-flex align-items-center"
           id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="ms-2 label brand-text">{{ Auth::user()->name }}</span>
            <i class="ms-2 bi bi-caret-down-fill"></i>
        </a>

        <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="{{ route('profil.edit') }}">{{ __('sidebar.profil') }}</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="dropdown-item" type="submit">{{ __('sidebar.deconnexion') }}</button>
                </form>
            </li>
        </ul>
    </div>
</div>
