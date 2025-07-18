<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Mon Application')</title>

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>
<body>

    <div class="container mt-4" style="max-width: 900px;">

        <!-- Header commun -->
        <header class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 border-bottom pb-3">
            <h2 class="mb-3 mb-md-0 text-center text-md-start fw-bold text-primary">
                Bienvenue, {{ auth()->user()->name }}
            </h2>
            <nav class="d-flex gap-3 justify-content-center justify-content-md-start flex-wrap">
                <a href="{{ route('demande.create_client') }}" class="btn btn-primary d-flex align-items-center gap-2 px-4 py-2 shadow-sm">
                    <i class="bi bi-plus-circle fs-5"></i> Nouvelle demande
                </a>
                <a href="{{ route('client.home') }}" class="btn btn-outline-primary d-flex align-items-center gap-2 px-4 py-2 shadow-sm">
                    <i class="bi bi-house fs-5"></i> Suivi ma demande
                </a>
            </nav>
        </header>

        <!-- Contenu spécifique à chaque page -->
        @yield('content')

    </div>

    <!-- Bootstrap JS Bundle (Popper + Bootstrap JS) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
