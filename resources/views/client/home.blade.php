@extends('layouts.client')

@section('title', 'Accueil Client')

@section('content')
<div class="container mt-5">
   @if(Auth::check())
    @php
        $heure = now()->format('H');
        $salutation = $heure < 12 ? 'Bonjour' : ($heure < 18 ? 'Bon après-midi' : 'Bonsoir');
        $user = Auth::user();
    @endphp


@else
    <h4 class="mb-4 text-danger text-end">Utilisateur non connecté</h4>
@endif


<h4 class="mb-4 text-primary">
    {{ $salutation }}, {{ Auth::user()->name }}
</h4>

    <h2 class="mb-4 fw-bold text-center">Mes Demandes de Traduction</h2>

    <div class="card shadow-sm border-0 rounded-4 p-4">
        <table class="table table-striped table-hover">
            <thead class="table-primary">
                <tr>
                    <th>nom du titulaire</th>
                    <th>Date</th>
                    <th>Langue Source</th>
                    <th>Langue Cible</th>
                    <th>Date Livraison</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($demandes as $demande)
                <tr>
                    <td>{{ $demande->nom_titulaire }}</td>
                    <td>{{ \Carbon\Carbon::parse($demande->created_at)->format('d/m/Y') }}</td>
                    <td>{{ $demande->langue_origine }}</td>
                    <td>{{ $demande->langue_souhaitee }}</td>
                    <td>
                        {{ $demande->date_fin ? \Carbon\Carbon::parse($demande->date_fin)->format('d/m/Y') : '—' }}
                    </td>
                    <td>
                        @php
                            $statusLabels = [
                                'en_attente' => 'En attente',
                                'en_cours' => 'En cours',
                                'terminee' => 'Terminée',
                            ];
                            $statusColors = [
                                'en_attente' => 'bg-info',
                                'en_cours' => 'bg-warning',
                                'terminee' => 'bg-success',
                            ];
                        @endphp
                        <span class="badge {{ $statusColors[$demande->status] ?? 'bg-secondary' }}">
                            {{ $statusLabels[$demande->status] ?? '—' }}
                        </span>
                    </td>
                    <td>
                        <button
                            class="btn btn-sm btn-outline-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#modal-{{ $demande->id }}">
                            Détails
                        </button>
                    </td>
                </tr>
{{--  --}}
                <!-- Modal -->
           <div class="modal fade" id="modal-{{ $demande->id }}" tabindex="-1" aria-labelledby="modalLabel-{{ $demande->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel-{{ $demande->id }}">Détails de la demande</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>

      <div class="modal-body">
        <p><strong>Langue d'origine :</strong> {{ $demande->langue_origine }}</p>
        <p><strong>Langue souhaitée :</strong> {{ $demande->langue_souhaitee }}</p>

        <p><strong>Date de début :</strong>
          {{ $demande->date_debut ? \Carbon\Carbon::parse($demande->date_debut)->format('d/m/Y') : 'Non définie' }}
        </p>

        <p><strong>Date de livraison :</strong>
          {{ $demande->date_fin ? \Carbon\Carbon::parse($demande->date_fin)->format('d/m/Y') : 'Non définie' }}
        </p>

        <p><strong>Prix total :</strong>
          {{ $demande->prix_total ? number_format($demande->prix_total, 2, ',', ' ') . ' MAD' : 'Pas encore défini' }}
        </p>

        <p>
          <strong>Statut :</strong>
          <span class="badge {{ $statusColors[$demande->status] ?? 'bg-secondary' }}">
            {{ $statusLabels[$demande->status] ?? '—' }}
          </span>
        </p>

        @if ($demande->status === 'en_cours')
          <div class="alert alert-warning mt-3">Votre demande est en cours de traitement.</div>
        @elseif ($demande->status === 'en_attente')
          <div class="alert alert-info mt-3">Votre demande est en attente de traitement.</div>
        @elseif ($demande->status === 'terminee')
          <div class="alert alert-success mt-3">Votre demande a été traitée avec succès.</div>
        @endif

        <h5 class="mt-4">📎 Fichiers envoyés :</h5>
        <ul class="list-group">
          @forelse ($demande->fichiers->where('type', 'initial') as $fichier)
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <span>{{ basename($fichier->chemin) }}</span>
              <div class="d-flex gap-2">
                <a href="{{ asset('storage/' . $fichier->chemin) }}" target="_blank" class="btn btn-sm btn-info">Voir</a>
                <a href="{{ route('suivi_demande.download', ['id' => $demande->id, 'fichierId' => $fichier->id]) }}" class="btn btn-sm btn-primary">Télécharger</a>
              </div>
            </li>
          @empty
            <li class="list-group-item fst-italic">Aucun fichier envoyé.</li>
          @endforelse
        </ul>

        @if ($demande->status === 'terminee')
<h5 class="mt-4 text-success">
  ✅ Fichiers traduits :

@if (!$demande->confirme_par_client)
    <form method="POST" action="{{ route('demande.confirmer_reception', $demande->id) }}" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-success"
            onclick="return confirm('Êtes-vous sûr de vouloir confirmer la réception des fichiers traduits ?');">
            ✅ Confirmer réception correcte
        </button>
    </form>
     <h5 class="mt-4">💬 Messages de l'administration :</h5>


@else
    <div class="mt-2">
        <span class="badge bg-success">✅ Réception déjà confirmée par vous </span>
    </div>
@endif

{{-- message de admin  --}}
 @if ($demande->messages->count() > 0)
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="bi bi-envelope"></i> Messages reçus
        </div>
        <ul class="list-group list-group-flush">
            @foreach ($demande->messages as $message)
                <li class="list-group-item">
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold"><i class="bi bi-chat-left-text"></i> Message</span>
                        <small class="text-muted">{{ $message->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                    <p class="mb-0 mt-2">{{ $message->contenu }}</p>
                </li>
            @endforeach
        </ul>
    </div>
@else
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Aucun message reçu pour cette demande.
    </div>
@endif


</h5>


          <ul class="list-group">
            @forelse ($demande->fichiers->where('type', 'final') as $fichierTraduit)
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>{{ basename($fichierTraduit->chemin) }}</span>
                <div class="d-flex gap-2">
                  <a href="{{ asset('storage/' . $fichierTraduit->chemin) }}" target="_blank" class="btn btn-sm btn-info">Voir</a>
                  <a href="{{ route('suivi_demande.download', ['id' => $demande->id, 'fichierId' => $fichierTraduit->id]) }}" class="btn btn-sm btn-success">Télécharger</a>
                </div>
              </li>
            @empty
              <li class="list-group-item fst-italic">Aucun fichier traduit disponible.</li>
            @endforelse
          </ul>
        @endif

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Aucune demande trouvée.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3 d-flex justify-content-center">
            {{ $demandes->links() }}
        </div>
    </div>
</div>
@endsection
