@extends('layouts.app')

@section('title', __('demandes_confirmes.title'))

@section('content')
<div class="container mt-5">

    <h2 class="mb-4 text-center fw-bold">{{ __('demandes_confirmes.title') }}</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ __('demandes_confirmes.alert_success') }} : {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('demandes_confirmes.table.close_button') }}"></button>
        </div>
    @endif

    <div class="table-responsive shadow-sm rounded-4 bg-white p-3">
        <table class="table table-hover align-middle" style="min-width: 1000px;">
            <thead class="table-light">
                <tr>
                    <th>{{ __('demandes_confirmes.table.nom_titulaire') }}</th>
                    <th>{{ __('demandes_confirmes.table.nom_demandeur') }}</th>
                    <th>{{ __('demandes_confirmes.table.date_fin') }}</th>
                    <th>{{ __('demandes_confirmes.table.status') }}</th>
                    <th>{{ __('demandes_confirmes.table.client') }}</th>
                    <th>{{ __('demandes_confirmes.table.prix_total') }}</th>
                    <th>{{ __('demandes_confirmes.table.adresse') }}</th>
                    <th class="text-center">{{ __('demandes_confirmes.table.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($demandes as $demande)
                    <tr>
                        <td class="fw-semibold">{{ $demande->nom_titulaire }}</td>
                        <td>{{ $demande->nom_demandeur }}</td>
                        <td>{{ $demande->date_fin ? $demande->date_fin->format('d/m/Y') : '—' }}</td>
                        <td><span class="badge bg-success">{{ __('demandes_confirmes.table.status_confirmed') }}</span></td>
                        <td>{{ $demande->user->name ?? '—' }}</td>
                        <td>{{ $demande->prix_total ?? '—' }}</td>
                        <td style="max-width: 180px; white-space: normal;">{{ $demande->adresse ?? '—' }}</td>
                        <td class="text-center" style="white-space: nowrap;">
                            <div class="btn-group" role="group" aria-label="{{ __('demandes_confirmes.table.actions') }}">
                                @if($demande->messages->count() > 0)
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#voirMessagesModal-{{ $demande->id }}" title="{{ __('demandes_confirmes.table.view_messages') }}">
                                    <i class="bi bi-chat-dots"></i>
                                </button>
                                @endif
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#messageModal-{{ $demande->id }}" title="{{ __('demandes_confirmes.table.send_message') }}">
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
                                        <h5 class="modal-title" id="messageModalLabel-{{ $demande->id }}">
                                            {{ __('demandes_confirmes.table.send_message_modal_title') }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('demandes_confirmes.table.close_button') }}"></button>
                                    </div>
                                    <div class="modal-body">
                                        <textarea name="contenu" class="form-control" rows="5" required>{{
                                            __('demandes_confirmes.table.message_placeholder', [
                                                'adresse' => $demande->adresse ?? __('demandes_confirmes.table.no_messages'),
                                                'prix' => $demande->prix_total ?? 'non défini'
                                            ])
                                        }}</textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">{{ __('demandes_confirmes.table.send_button') }}</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('demandes_confirmes.table.cancel_button') }}</button>
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
                                    <h5 class="modal-title">
                                        {{ __('demandes_confirmes.table.messages_history_title', ['id' => $demande->id]) }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('demandes_confirmes.table.close_button') }}"></button>
                                </div>
                                <div class="modal-body">
                                    @foreach($demande->messages as $message)
                                        <div class="border rounded p-3 mb-3">
                                            <div class="small text-muted mb-1">
                                                {{ __('Envoyé le') }} {{ $message->created_at->format('d/m/Y H:i') }}
                                            </div>
                                            <div>{{ $message->contenu }}</div>
                                        </div>
                                    @endforeach

                                    @if($demande->messages->isEmpty())
                                        <p class="text-muted fst-italic">{{ __('demandes_confirmes.table.no_messages') }}</p>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">{{ __('demandes_confirmes.table.close_button') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                @empty
                    <tr>
                        <td colspan="8" class="text-center fst-italic">{{ __('demandes_confirmes.no_demandes') }}</td>
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
