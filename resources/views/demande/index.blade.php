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
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Status</th>
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
                        <td>{{ \Carbon\Carbon::parse($demande->date_debut)->format('d/m/Y') }}</td>
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

                        <td class="text-center">
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

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center fst-italic">Aucune demande trouvée.</td>
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




</script>
@endsection
