@extends('layouts.app')

@section('title', 'Demandes Confirmées par Client')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Demandes Confirmées par le Client</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nom titulaire</th>
                <th>Nom demandeur</th>
                <th>Date fin</th>
                <th>Status</th>
                <th>Client</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($demandes as $demande)
                <tr>
                    <td>{{ $demande->nom_titulaire }}</td>
                    <td>{{ $demande->nom_demandeur }}</td>
                    <td>{{ $demande->date_fin ? $demande->date_fin->format('d/m/Y') : '—' }}</td>
                    <td><span class="badge bg-success">Confirmée</span></td>
                    <td>{{ $demande->user->name ?? '—' }}</td>
                    <td>
                        <!-- Bouton pour ouvrir la modal -->
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#messageModal-{{ $demande->id }}">
                            Envoyer message
                        </button>
                    </td>
                </tr>

                <!-- Modal pour cette demande -->
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
                                    <textarea name="contenu" class="form-control" rows="5" required placeholder="Écrivez votre message ici..."></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Envoyer</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <tr><td colspan="6" class="text-center fst-italic">Aucune demande confirmée.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $demandes->links() }}
    </div>
</div>
@endsection
