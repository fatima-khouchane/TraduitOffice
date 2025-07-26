@extends('layouts.app')

@section('title', __('dashboard.title'))

@section('content')
<div class="container py-5">
    <h2 class="mb-5 text-center fw-bold">📊 {{ __('dashboard.heading') }}</h2>

    <!-- Statistiques Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card bg-white shadow-lg border-0 rounded-4 text-center py-4">
                <h6 class="text-muted">{{ __('dashboard.documents_termines') }}</h6>
                <h3 class="fw-bold text-success">{{ $documents_termines }}</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-white shadow-lg border-0 rounded-4 text-center py-4">
                <h6 class="text-muted">{{ __('dashboard.demandes_en_cours') }}</h6>
                <h3 class="fw-bold text-warning">{{ $documents_en_cours }}</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-white shadow-lg border-0 rounded-4 text-center py-4">
                <h6 class="text-muted">{{ __('dashboard.clients_en_ligne') }}</h6>
                <h3 class="fw-bold text-primary">{{ $clients_en_ligne }}</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-white shadow-lg border-0 rounded-4 text-center py-4">
                <h6 class="text-muted">{{ __('dashboard.langue_plus_traduite') }}</h6>
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
        <h5 class="mb-4 fw-bold text-center">🏷️ {{ __('dashboard.top_categories') }}</h5>
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
            <label class="input-group-text" for="annee">📅 {{ __('dashboard.annee') }}</label>
            <input type="number" min="2000" max="{{ now()->year }}" step="1" name="annee" id="annee" class="form-control"
                   value="{{ request('annee', now()->year) }}" onchange="this.form.submit()">
        </div>
    </form>

    <!-- Graphe évolution mensuelle -->
    <div class="card bg-white shadow-lg border-0 rounded-4 p-4 mb-5">
        <h5 class="mb-4 fw-bold text-center">📈 {{ __('dashboard.evolution') }}</h5>
        <div style="height: 300px;">
            <canvas id="demandesChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('demandesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($mois) !!},
                datasets: [{
                    label: '{{ __("dashboard.graph_label") }}',
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
                            stepSize: 1,
                            callback: function(value) {
                                return Number.isInteger(value) ? value : '';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
