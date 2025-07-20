@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 fw-bold text-center">Liste des demandes traduites</h2>

    <div class="card shadow-sm border-0 rounded-4 p-4">

        <!-- Filtres -->
     <div class="d-flex flex-wrap gap-3 align-items-end justify-content-between mb-4">

    <!-- Recherche -->
    <div class="flex-grow-1">
        <input type="text"
               id="searchInput"
               class="form-control shadow-sm rounded-3"
               placeholder="Rechercher par nom, prénom ou CIN...">
    </div>

    <!-- Filtre par saisie -->
    <div>
        <select id="filterSaisiePar" class="form-select shadow-sm rounded-3">
            <option value="all" selected>Filtrer par source</option>
            <option value="online">En ligne</option>
            <option value="agency">Agence</option>
        </select>
    </div>

    <!-- Mois + Bouton imprimer -->
    <form id="printForm" class="d-flex align-items-end gap-2 flex-wrap" method="GET" action="{{ route('demande.imprimer_pdf') }}" target="_blank">
        <div>
            <label for="mois" class="form-label mb-1">choisir le mois pour imprimer le bilan :</label>
            <input type="month" name="mois" id="mois" class="form-control form-control-sm" style="min-width: 150px;" required>
        </div>
        <div>
            <button type="submit" class="btn btn-outline-primary btn-sm mt-4">
                🖨️ Imprimer PDF
            </button>
        </div>
    </form>

</div>

        <!-- Conteneur tableau avec largeur max et scroll horizontal -->
        <div style="max-width: 1200px; overflow-x:auto; margin: auto;">
            <table class="table table-striped table-hover align-middle mb-0" style="width: 100%; min-width: 960px;">
                <thead class="table-primary">
                    <tr>
                       <th>nom titulaire</th>
                        <th>nom demandeur</th>
                        <th>CIN</th>
                        <th>Téléphone</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Status</th>
                        <th>Traducteur</th>
                        <th>Demande saisie par</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="demandesTable">
                    @forelse ($demandes as $demande)
                    <tr data-saisie-par="{{ $demande->is_online ? 'online' : 'agency' }}">
                        <td class="fw-semibold">{{ $demande->nom_titulaire }}</td>
                        <td>{{ $demande->nom_demandeur }}</td>
                        <td>{{ $demande->cin }}</td>
                        <td>{{ $demande->telephone }}</td>
                        <td>{{ \Carbon\Carbon::parse($demande->date_debut)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($demande->date_fin)->format('d/m/Y') }}</td>
                        <td>
                            @php
                                $statusLabels = [
                                    'en_cours' => 'En cours',
                                    'terminee' => 'Terminée',
                                    'annulee' => 'Annulée',
                                ];
                            @endphp
                            <span class="badge
                                {{ $demande->status === 'en_cours' ? 'bg-warning' : '' }}
                                {{ $demande->status === 'terminee' ? 'bg-success' : '' }}
                                {{ $demande->status === 'annulee' ? 'bg-danger' : '' }}">
                                {{ $statusLabels[$demande->status] ?? '—' }}
                            </span>
                        </td>
                        <td>{{ $demande->translator->name ?? '—' }}</td>
                        <td>
                            @if($demande->is_online)
                                <span class="badge bg-success">En ligne</span>
                            @else
                                <span class="badge bg-secondary">Agence</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('suivi_demande.show', ['id' => $demande->id, 'traduit' => true]) }}" class="btn btn-sm btn-info" title="Voir les détails">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center fst-italic">Aucune demande trouvée.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pagination Laravel -->
<div class="mt-3 d-flex justify-content-center">
    {{ $demandes->links() }}
</div>

<!-- Scripts -->
<script>
    const searchInput = document.getElementById('searchInput');
    const filterSelect = document.getElementById('filterSaisiePar');

    function filterTable() {
        const filterText = searchInput.value.toLowerCase();
        const filterSaisie = filterSelect.value;
        const rows = document.querySelectorAll('#demandesTable tr');

        rows.forEach(row => {
            const nom = row.cells[0]?.textContent.toLowerCase() || '';
            const prenom = row.cells[1]?.textContent.toLowerCase() || '';
            const cin = row.cells[2]?.textContent.toLowerCase() || '';
            const saisiePar = row.getAttribute('data-saisie-par');

            const matchesSearch = nom.includes(filterText) || prenom.includes(filterText) || cin.includes(filterText);
            const matchesSaisie = (filterSaisie === 'all') || (saisiePar === filterSaisie);

            row.style.display = (matchesSearch && matchesSaisie) ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);
    filterSelect.addEventListener('change', filterTable);
</script>
@endsection
