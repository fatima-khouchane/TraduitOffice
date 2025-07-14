@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Ton contenu -->
    <h2 class="mb-4 fw-bold text-center">Liste des demandes pas encore traité</h2>

    <div class="card shadow-sm border-0 rounded-4 p-4">

        <!-- Champ de recherche -->
        <div class="d-flex justify-content-end mb-4">
            <input type="text"
                   id="searchInput"
                   class="form-control w-50 shadow-sm rounded-3"
                   placeholder="Rechercher par nom ou CIN...">
        </div>

        <!-- Tableau -->
        <div style="max-width: 1200px; overflow-x:auto; margin: auto;">
            <table class="table table-striped table-hover align-middle mb-0" style="width: 960px;">
                <thead class="table-primary">
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>CIN</th>
                        <th>Téléphone</th>
                        {{-- <th>Date début</th> --}}
                        <th>Date de livraison</th>
                        <th>Status</th>
                        <th>Traducteur</th>

                        <th class="text-center">Actions</th>

                    </tr>
                </thead>
                <tbody id="demandesTable">
                    @forelse ($demandes as $demande)
                    <tr>
                        <td class="fw-semibold">{{ $demande->nom }}</td>
                        <td>{{ $demande->prenom }}</td>
                        <td>{{ $demande->cin }}</td>
                        <td>{{ $demande->telephone }}</td>
                        {{-- <td>{{ \Carbon\Carbon::parse($demande->date_debut)->format('d/m/Y') }}</td> --}}
                        <td>{{ \Carbon\Carbon::parse($demande->date_fin)->format('d/m/Y') }}</td>
                        <td>
                            <div class="col-md-6">
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
                            </div>
                          </td>
                        <td style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                            title="{{ $demande->translator->name ?? '' }}">
                            {{ $demande->translator->name ?? '—' }}
                        </td>

                        <td class="text-center" style="white-space: nowrap;">
                            <a href="{{ route('suivi_demande.show', ['id' => $demande->id, 'traduit' => false]) }}" class="btn btn-sm btn-info" title="Voir les détails">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('suivi_demande.edit', $demande->id) }}" class="btn btn-sm btn-warning ms-2" title="Modifier la demande">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('suivi_demande.destroy', $demande->id) }}" method="POST" class="d-inline ms-2"
                                onsubmit="return confirm('Êtes-vous sûr(e) de vouloir supprimer cette demande ?');">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-danger" title="Supprimer la demande">
                                  <i class="bi bi-trash"></i>
                              </button>
                          </form>
                    @if(Auth::user()->role === 'admin')
                            <button type="button"
                                    class="btn btn-sm btn-success ms-2"
                                    data-bs-toggle="modal"
                                    data-bs-target="#affecterModal"
                                    data-demande-id="{{ $demande->id }}">
                                <i class="bi bi-person-plus"></i> Affecter
                            </button>
                        @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center fst-italic">Aucune demande trouvée.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <!-- Modal Affecter -->
<div class="modal fade" id="affecterModal" tabindex="-1" aria-labelledby="affecterModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="" id="affecterForm">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="affecterModalLabel">Affecter un traducteur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="demande_id" id="demandeIdField">

                <div class="mb-3">
                    <label for="translator_id" class="form-label">Sélectionner un traducteur</label>
                    <select name="translator_id" id="translatorSelect" class="form-select" required>
                        <option value="">-- Choisir --</option>
                        @foreach($traducteurs as $traducteur)
                            <option value="{{ $traducteur->id }}">{{ $traducteur->name }} ({{ $traducteur->email }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Affecter</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            </div>
        </div>
    </form>
  </div>
</div>

        </div>
    </div>
</div>

<!-- Pagination Laravel -->
<div class="mt-3 d-flex justify-content-center">
    {{ $demandes->links() }}
</div>

<!-- Script placé à la fin de la section content -->
<script>
    document.getElementById('searchInput').addEventListener('input', function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#demandesTable tr');

        rows.forEach(row => {
            const nom = row.cells[0]?.textContent.toLowerCase() || '';
            const prenom = row.cells[1]?.textContent.toLowerCase() || '';
            const cin = row.cells[2]?.textContent.toLowerCase() || '';

            if (nom.includes(filter) || prenom.includes(filter) || cin.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });




document.addEventListener('DOMContentLoaded', function () {
    const affecterModal = document.getElementById('affecterModal');
    const form = document.getElementById('affecterForm');
    const demandeIdField = document.getElementById('demandeIdField');

    affecterModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const demandeId = button.getAttribute('data-demande-id');

        // injecte l’ID dans le champ caché et action du formulaire
        demandeIdField.value = demandeId;
        form.action = `/admin/demandes/${demandeId}/affecter`; // même route définie dans web.php
    });
});
</script>
@endsection
