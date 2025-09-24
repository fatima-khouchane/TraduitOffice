@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 fw-bold text-center">{{ __('demandes_ter.Liste_des_demandes_traduites') }}
</h2>


    <div class="card shadow-sm border-0 rounded-4 p-4">

        <!-- Filtres -->
        <div class="d-flex flex-wrap gap-3 align-items-end justify-content-between mb-4">
            <!-- Recherche -->
            <div class="flex-grow-1">
                <input type="text"
                       id="searchInput"
                       class="form-control shadow-sm rounded-3"
                       placeholder="{{ __('demandes_ter.Rechercher_par_nom_prenom_cin') }}">
            </div>

            <!-- Filtre par saisie -->
            <div>
                <select id="filterSaisiePar" class="form-select shadow-sm rounded-3">
                    <option value="all" selected>{{ __('demandes_ter.Filtrer_par_source') }}</option>
                    <option value="online">{{ __('demandes_ter.En_ligne') }}</option>
                    <option value="agency">{{ __('demandes_ter.Agence') }}</option>
                </select>
            </div>

            <!-- Mois + Boutons PDF & Excel -->
            <form class="d-flex align-items-end gap-2 flex-wrap" method="GET">
                <div>
                    <label for="mois" class="form-label mb-1">{{ __('demandes_ter.Choisir_le_mois') }}</label>
                    <input type="month" name="mois" id="mois" class="form-control form-control-sm" required style="min-width: 150px;">
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" formaction="{{ route('demande.imprimer_pdf') }}" formtarget="_blank" class="btn btn-outline-primary btn-sm">
                        🖨️ {{ __('demandes_ter.Imprimer_PDF') }}
                    </button>
                    <button type="submit" formaction="{{ route('export.demandes') }}" class="btn btn-success btn-sm">
                        <i class="bi bi-file-earmark-excel"></i> {{ __('demandes_ter.Telecharger_Excel') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Tableau -->
       <div class="table-responsive">
           <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-primary">
                    <tr>
                        <th>{{ __('demandes_ter.nom_titulaire') }}</th>
                        <th>{{ __('demandes_ter.nom_demandeur') }}</th>
                        <th>{{ __('demandes_ter.CIN') }}</th>
                        <th>{{ __('demandes_ter.Telephone') }}</th>
                        <th>{{ __('demandes_ter.Date_debut') }}</th>
                        <th>{{ __('demandes_ter.Date_fin') }}</th>
                        <th>{{ __('demandes_ter.Status') }}</th>
                        <th>{{ __('demandes_ter.Traducteur') }}</th>
                        <th>{{ __('demandes_ter.Demande_saisie_par') }}</th>
                        <th class="text-center">{{ __('demandes_ter.Actions') }}</th>
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
                                    'en_cours' => __('demandes_ter.En_cours'),
                                    'terminee' => __('demandes_ter.Terminee'),
                                ];
                            @endphp
                            <span class="badge
                                {{ $demande->status === 'en_cours' ? 'bg-warning' : '' }}
                                {{ $demande->status === 'terminee' ? 'bg-success' : '' }}
                 ">
                                {{ $statusLabels[$demande->status] ?? '—' }}
                            </span>
                        </td>
                        <td>{{ $demande->translator->name ?? '—' }}</td>
                        <td>
                            @if($demande->is_online)
                                <span class="badge bg-success">{{ __('demandes_ter.En_ligne') }}</span>
                            @else
                                <span class="badge bg-secondary">{{ __('demandes_ter.Agence') }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('suivi_demande.show', ['id' => $demande->id, 'traduit' => true]) }}" class="btn btn-sm btn-info" title="{{ __('demandes_ter.Voir_details') }}">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center fst-italic">{{ __('demandes_ter.Aucune_demande_trouvee') }}</td>
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
