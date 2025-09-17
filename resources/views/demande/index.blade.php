@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 fw-bold text-center">{{ __('demandes.list_title') }}</h2>

    <div class="card shadow-sm border-0 rounded-4 p-4">

        <!-- Recherche et filtre -->
        <div class="d-flex flex-wrap justify-content-between mb-4">
            <input type="text" id="searchInput" class="form-control w-50 shadow-sm rounded-3 mb-2" placeholder="{{ __('demandes.search_placeholder') }}">

            <select id="sourceFilter" class="form-select w-auto shadow-sm rounded-3 mb-2">
                <option value="all">{{ __('demandes.filter_all') }}</option>
                <option value="client">{{ __('demandes.filter_client') }}</option>
                <option value="agence">{{ __('demandes.filter_agence') }}</option>
            </select>
        </div>

        <!-- Tableau -->
        <div style="max-width: 1200px; overflow-x:auto; margin: auto;">
            <table class="table table-striped table-hover align-middle mb-0" style="width: 960px;">
                <thead class="table-primary">
                    <tr>
                        <th>{{ __('demandes.nom_titulaire') }}
                             @if(!empty($demande->documents))
        <br>
        <small class="text-muted" style="font-size: 0.85em;">
            @foreach($demande->documents as $doc)
                <span class="badge bg-secondary me-1 mb-1">
                    {{ __('documents.categories.' . $doc['categorie']) }} :
                    {{ __('documents.types.' . $doc['sous_type']) }}
                </span>
            @endforeach
        </small>
    @endif
                        </th>
                        <th>{{ __('demandes.nom_demandeur') }}</th>
                        <th>{{ __('demandes.cin') }}</th>
                        <th>{{ __('demandes.telephone') }}</th>
                        <th>{{ __('demandes.date_livraison') }}</th>
                        <th>{{ __('demandes.status') }}</th>
                        <th>{{ __('demandes.traducteur') }}</th>
                        <th>{{ __('demandes.saisie_par') }}</th>
                        <th class="text-center">{{ __('demandes.actions') }}</th>
                    </tr>
                </thead>
                <tbody id="demandesTable">
                    @forelse ($demandes as $demande)
                    <tr data-source="{{ $demande->is_online ? 'client' : 'agence' }}">
                        <td class="fw-semibold">{{ $demande->nom_titulaire }}</td>
                        <td>{{ $demande->nom_demandeur }}</td>
                        <td>{{ $demande->cin }}</td>
                        <td>{{ $demande->telephone }}</td>
                        <td>{{ \Carbon\Carbon::parse($demande->date_fin)->format('d/m/Y') }}</td>

                        <td>
                            @php
                                $statusLabels = ['en_attente' => __('demandes.en_attente'), 'en_cours' => __('demandes.en_cours')];
                                $statusColors = ['en_attente' => 'bg-info', 'en_cours' => 'bg-warning'];
                            @endphp
                            <span class="badge {{ $statusColors[$demande->status] ?? 'bg-secondary' }}">
                                {{ $statusLabels[$demande->status] ?? '—' }}
                            </span>
                        </td>

                        <td style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $demande->translator->name ?? '' }}">
                            {{ $demande->translator->name ?? '—' }}
                        </td>

                        <td>
                            <span class="badge {{ $demande->is_online ? 'bg-success' : 'bg-secondary' }}">
                                {{ $demande->is_online ? __('demandes.source_client') : __('demandes.source_agence') }}
                            </span>
                        </td>

                        <td class="text-center" style="white-space: nowrap;">
                            <a href="{{ route('suivi_demande.show', ['id' => $demande->id, 'traduit' => false]) }}" class="btn btn-sm btn-info" title="{{ __('demandes.view') }}">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('suivi_demande.edit', $demande->id) }}" class="btn btn-sm btn-warning ms-2" title="{{ __('demandes.edit') }}">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('suivi_demande.destroy', $demande->id) }}" method="POST" class="d-inline ms-2"
                                  onsubmit="return confirm('{{ __('demandes.confirm_delete') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="{{ __('demandes.delete') }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @if(Auth::user()->role === 'admin')
                            <button type="button"
                                    class="btn btn-sm btn-success ms-2"
                                    data-bs-toggle="modal"
                                    data-bs-target="#affecterModal"
                                    data-demande-id="{{ $demande->id }}">
                                <i class="bi bi-person-plus"></i> {{ __('demandes.assign') }}
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center fst-italic">{{ __('demandes.no_demandes') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Affecter -->
<div class="modal fade" id="affecterModal" tabindex="-1" aria-labelledby="affecterModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="" id="affecterForm">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('demandes.modal_title') }}</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" name="demande_id" id="demandeIdField">
                <div class="mb-3">
                    <label for="translator_id" class="form-label">{{ __('demandes.select_translator') }}</label>
                    <select name="translator_id" id="translatorSelect" class="form-select" required>
                        <option value="">-- {{ __('demandes.choose') }} --</option>
                        @foreach($traducteurs as $traducteur)
                            <option value="{{ $traducteur->id }}">{{ $traducteur->name }} ({{ $traducteur->email }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">{{ __('demandes.assign') }}</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('demandes.cancel') }}</button>
            </div>
        </div>
    </form>
  </div>
</div>

<!-- Pagination -->
<div class="mt-3 d-flex justify-content-center">
    {{ $demandes->links() }}
</div>

<!-- Script de filtre -->
<script>
document.getElementById('searchInput').addEventListener('input', filterRows);
document.getElementById('sourceFilter').addEventListener('change', filterRows);

function filterRows() {
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const selectedSource = document.getElementById('sourceFilter').value;
    const rows = document.querySelectorAll('#demandesTable tr');

    rows.forEach(row => {
        const nom = row.cells[0]?.textContent.toLowerCase() || '';
        const prenom = row.cells[1]?.textContent.toLowerCase() || '';
        const cin = row.cells[2]?.textContent.toLowerCase() || '';
        const source = row.dataset.source;

        const matchesSearch = nom.includes(searchValue) || prenom.includes(searchValue) || cin.includes(searchValue);
        const matchesSource = selectedSource === 'all' || source === selectedSource;

        row.style.display = (matchesSearch && matchesSource) ? '' : 'none';
    });
}

// Modal
document.addEventListener('DOMContentLoaded', function () {
    const affecterModal = document.getElementById('affecterModal');
    const form = document.getElementById('affecterForm');
    const demandeIdField = document.getElementById('demandeIdField');

    affecterModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const demandeId = button.getAttribute('data-demande-id');
        demandeIdField.value = demandeId;
        form.action = `/admin/demandes/${demandeId}/affecter`;
    });
});
</script>
@endsection
