@extends('layouts.app')

@section('title', 'Dashboard Statistiques')

@section('content')
<div class="container py-5">
    <h2 class="mb-5 text-center fw-bold">📊 Tableau de bord - Statistiques</h2>

    <!-- Statistiques Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card bg-white shadow-lg border-0 rounded-4 text-center py-4">
                <h6 class="text-muted">Documents terminés</h6>
                <h3 class="fw-bold text-success">{{ $documents_termines }}</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-white shadow-lg border-0 rounded-4 text-center py-4">
                <h6 class="text-muted">Demandes en cours</h6>
                <h3 class="fw-bold text-warning">{{ $documents_en_cours }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-white shadow-lg border-0 rounded-4 text-center py-4">
                <h6 class="text-muted">Clients ayant fait une demande en ligne</h6>
                <h3 class="fw-bold text-primary">{{ $clients_en_ligne }}</h3>
            </div>

        </div>


        <div class="col-md-4">
            <div class="card bg-white shadow-lg border-0 rounded-4 text-center py-4">
                <h6 class="text-muted">Langue la plus traduite</h6>
                @if($langue_plus_traduite)
                    <h4 class="fw-bold text-info">
                        {{ $langue_plus_traduite->langue_origine }} → {{ $langue_plus_traduite->langue_souhaitee }}
                    </h4>
                @else
                    <h3 class="fw-bold text-info">N/A</h3>
                @endif
            </div>
        </div>
    </div>

    <!-- Top 5 catégories de documents -->
    <div class="card bg-white shadow-lg border-0 rounded-4 p-4 mb-5">
        <h5 class="mb-4 fw-bold text-center">🏷️ Top 5 catégories de documents</h5>
        <ul class="list-group">
            @foreach ($categories_top as $cat)
                <li class="list-group-item d-flex justify-content-between">
                    {{ $cat->categorie_complete }}
                    <span class="badge bg-success rounded-pill">{{ $cat->total }}</span>
                </li>
            @endforeach
        </ul>
    </div>


    <form method="GET" action="{{ route('statistique') }}" class="mb-4 d-flex justify-content-end">
        <div class="input-group" style="max-width: 200px;">
            <label class="input-group-text" for="annee">📅 Année</label>
            <input type="number" min="2000" max="{{ now()->year }}" step="1" name="annee" id="annee" class="form-control"
                   value="{{ request('annee', now()->year) }}" onchange="this.form.submit()">
        </div>
    </form>


    <!-- Graphe évolution mensuelle -->
    <div class="card bg-white shadow-lg border-0 rounded-4 p-4 mb-5">
        <h5 class="mb-4 fw-bold text-center">📈 Évolution mensuelle des fichiers traduits</h5>
        <div style="height: 300px;">
            <canvas id="demandesChart"></canvas>
        </div>
    </div>
</div>



    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        console.log('heloo')
        document.addEventListener('DOMContentLoaded', function () {
            console.log("✅ Script chargé"); // test

            const ctx = document.getElementById('demandesChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($mois) !!},
                    datasets: [{
                        label: 'Nombre de fichier traduit ce mois :',
                        data: {!! json_encode($valeurs) !!},
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5
                    }]
                },
                options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1, // 👈 Force des pas de 1
                    callback: function(value) {
                        return Number.isInteger(value) ? value : ''; // 👈 Affiche uniquement les entiers
                    }
                }
            }
        }
    }

            });
        });
    </script>
@endsection
