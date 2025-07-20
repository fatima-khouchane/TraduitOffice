@extends('layouts.app')

@section('title', 'Demandes Confirmées par Client')

@section('content')
<div class="container mt-5">

    <h2 class="mb-4 text-center fw-bold">Demandes Confirmées par le Client</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    <div class="table-responsive shadow-sm rounded-4 bg-white p-3">
        <table class="table table-hover align-middle" style="min-width: 1000px;">
            <thead class="table-light">
                <tr>
                    <th>Nom titulaire</th>
                    <th>Nom demandeur</th>
                    <th>Date fin</th>
                    <th>Status</th>
                    <th>Client</th>
                    <th>Prix total (MAD)</th>
                    <th>Adresse</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($demandes as $demande)
                    <tr>
                        <td class="fw-semibold">{{ $demande->nom_titulaire }}</td>
                        <td>{{ $demande->nom_demandeur }}</td>
                        <td>{{ $demande->date_fin ? $demande->date_fin->format('d/m/Y') : '—' }}</td>
                        <td><span class="badge bg-success">Confirmée</span></td>
                        <td>{{ $demande->user->name ?? '—' }}</td>
                        <td>{{ $demande->prix_total ?? '—' }}</td>
                        <td style="max-width: 180px; white-space: normal;">{{ $demande->adresse ?? '—' }}</td>
                        <td class="text-center" style="white-space: nowrap;">
                            <div class="btn-group" role="group" aria-label="Actions">
                                @if($demande->messages->count() > 0)
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#voirMessagesModal-{{ $demande->id }}" title="Voir messages">
                                    <i class="bi bi-chat-dots"></i>
                                </button>
                                @endif
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#messageModal-{{ $demande->id }}" title="Envoyer message">
                                    <i class="bi bi-envelope"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal Envoyer message -->
                    <div class="modal fade" id="messageModal-{{ $demande->id }}" tabindex="-1" aria-labelledby="messageModalLabel-{{ $demande->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('admin.demande.envoyer_message', $demande->id) }}">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="messageModalLabel-{{ $demande->id }}">Envoyer un message au client</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                    </div>
                                    <div class="modal-body">
                                        <textarea name="contenu" class="form-control" rows="5" required>{{
"Bonjour,

Vos fichiers sont envoyés à l'adresse : " . ($demande->adresse ?? "Adresse non fournie") . ".
Le prix total pour cette demande est de " . ($demande->prix_total ?? "non défini") . " MAD.

Merci pour votre confiance." }}</textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Envoyer</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Modal Voir messages -->
                    <div class="modal fade" id="voirMessagesModal-{{ $demande->id }}" tabindex="-1" aria-labelledby="voirMessagesModalLabel-{{ $demande->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Historique des messages pour la demande #{{ $demande->id }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                </div>
                                <div class="modal-body">
                                    @foreach($demande->messages as $message)
                                        <div class="border rounded p-3 mb-3">
                                            <div class="small text-muted mb-1">
                                                Envoyé le {{ $message->created_at->format('d/m/Y H:i') }}
                                            </div>
                                            <div>{{ $message->contenu }}</div>
                                        </div>
                                    @endforeach

                                    @if($demande->messages->isEmpty())
                                        <p class="text-muted fst-italic">Aucun message envoyé pour cette demande.</p>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>

                @empty
                    <tr>
                        <td colspan="8" class="text-center fst-italic">Aucune demande confirmée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $demandes->links() }}
    </div>
</div>
@endsection
