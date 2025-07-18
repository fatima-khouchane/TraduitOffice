<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'TraduOffice')</title>

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar-nav .nav-link {
            position: relative;
            padding-bottom: 0.2rem;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            height: 2px;
            width: 0;
            background-color: #0d6efd;
            transition: width 0.3s ease-in-out;
        }

        .navbar-nav .nav-link:hover::after {
            width: 100%;
        }

        .navbar-nav .nav-link.active::after {
            width: 100%;
        }

        .dropdown-item:hover {
            background-color: #eef3ff;
            border-left: 3px solid #0d6efd;
        }
    </style>
</head>
<body>

    {{-- ✅ Barre de navigation moderne pour client --}}
    @if(Auth::user() && Auth::user()->role === 'client')
        <nav class="navbar navbar-expand-lg bg-white shadow-sm border-bottom rounded-bottom" style="z-index: 1030;">
            <div class="container py-2">
                <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-primary" href="#">
                    <i class="bi bi-translate fs-4 text-primary"></i> TraduitOffice
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
                    <ul class="navbar-nav align-items-center gap-3">
                   <li class="nav-item">
    <a class="nav-link position-relative fw-medium text-dark d-flex align-items-center gap-1 {{ request()->routeIs('client.messages') ? 'active' : '' }}"
       href="{{ route('client.messages') }}">
        <i class="bi bi-envelope text-primary fs-5"></i>
        <span>Messages</span>

        @php
            $demandeIds = \App\Models\Demande::where('user_id', Auth::id())->pluck('id');
            $unreadCount = \App\Models\Message::whereIn('demande_id', $demandeIds)
                ->where('is_read', false)
                ->count();
        @endphp

        @if($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $unreadCount }}
                <span class="visually-hidden">non lus</span>
            </span>
        @endif
    </a>
</li>


                        <li class="nav-item">
                            <a class="nav-link fw-medium text-dark d-flex align-items-center gap-1 {{ request()->routeIs('demande.create_client') ? 'active' : '' }}"
                               href="{{ route('demande.create_client') }}">
                                <i class="bi bi-plus-circle text-primary"></i>
                                <span>Nouvelle demande</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-medium text-dark d-flex align-items-center gap-1 {{ request()->routeIs('client.home') ? 'active' : '' }}"
                               href="{{ route('client.mes_demandes') }}">
                                <i class="bi bi-journal-text text-primary"></i>
                                <span>Suivi des demandes</span>
                            </a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle fs-5 text-primary"></i>
                                <span class="fw-semibold">{{ auth()->user()->name ?? 'Client' }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                                <li>
                                    <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="{{ route('profil.edit') }}">
                                        <i class="bi bi-person-lines-fill text-primary"></i> Mon Profil
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 d-flex align-items-center gap-2 text-danger">
                                            <i class="bi bi-box-arrow-right"></i> Déconnexion
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    @endif

    {{-- ✅ Contenu principal --}}
    <main class="py-4 container">
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
