

@extends('layouts.app')

@section('title', __('demandes_confirmes.title'))

@section('content')
<div class="container mt-5">

    <h2 class="mb-4 text-center fw-bold text-primary">{{ __('demandes_confirmes.title') }}</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ __('demandes_confirmes.alert_success') }} : {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('demandes_confirmes.table.close_button') }}"></button>
        </div>
    @endif

    <div class="row g-4">
        @forelse($demandes as $demande)
            <div class="col-md-6 col-lg-4">
                <div class="card demande-card shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold">{{ $demande->nom_titulaire }}</h5>
                        <p class="mb-1"><strong>{{ __('demandes_confirmes.table.nom_demandeur') }}:</strong> {{ $demande->nom_demandeur }}</p>
                        <p class="mb-1"><strong>{{ __('demandes_confirmes.table.date_fin') }}:</strong> {{ $demande->date_fin ? $demande->date_fin->format('d/m/Y') : '—' }}</p>
                        <p class="mb-1"><strong>{{ __('demandes_confirmes.table.status') }}:</strong>
                            <span class="badge bg-success">{{ __('demandes_confirmes.table.status_confirmed') }}</span>
                        </p>
                        <p class="mb-1"><strong>{{ __('demandes_confirmes.table.client') }}:</strong> {{ $demande->user->name ?? '—' }}</p>
                        <p class="mb-1"><strong>{{ __('demandes_confirmes.table.prix_total') }}:</strong> {{ $demande->prix_total ?? '—' }}</p>
                        <p class="mb-3"><strong>{{ __('demandes_confirmes.table.adresse') }}:</strong> {{ $demande->adresse ?? '—' }}</p>

                       <div class="mt-auto d-flex gap-2">
    @if($demande->messages->count() > 0)
        <button type="button" class="btn btn-outline-primary btn-sm flex-grow-1"
                data-bs-toggle="modal"
                data-bs-target="#voirMessagesModal-{{ $demande->id }}">
            <i class="bi bi-chat-dots"></i> {{ __('demandes_confirmes.table.view_messages') }}
        </button>
    @endif
    <button type="button" class="btn btn-primary btn-sm flex-grow-1"
            data-bs-toggle="modal"
            data-bs-target="#messageModal-{{ $demande->id }}">
        <i class="bi bi-envelope"></i> {{ __('demandes_confirmes.table.send_message') }}
    </button>
</div>

                    </div>
                </div>
            </div>

            <!-- Modal Envoyer message -->
            <div class="modal fade" id="messageModal-{{ $demande->id }}" tabindex="-1" aria-labelledby="messageModalLabel-{{ $demande->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('admin.demande.envoyer_message', $demande->id) }}">
                        @csrf
                        <div class="modal-content rounded-4 shadow">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="messageModalLabel-{{ $demande->id }}">
                                    {{ __('demandes_confirmes.table.send_message_modal_title') }}
                                </h5>
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
                    <div class="modal-content rounded-4 shadow">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">
                                {{ __('demandes_confirmes.table.messages_history_title', ['id' => $demande->id]) }}
                            </h5>
                        </div>
                        <div class="modal-body">
                            @forelse($demande->messages as $message)
                                <div class="border rounded p-3 mb-3 message-card">
                                    <div class="small text-muted mb-1">
                                         {{ __('demandes_confirmes.table.sent_at') }} {{ $message->created_at->format('d/m/Y H:i') }}
                                    </div>
  <div>
        {{ __('demandes_confirmes.table.message_placeholder', [
            'adresse' => $demande->adresse ?? __('demandes_confirmes.table.no_messages'),
            'prix' => $demande->prix_total ?? '—'
        ]) }}
    </div>                                </div>
                            @empty
                                <p class="text-muted fst-italic">{{ __('demandes_confirmes.table.no_messages') }}</p>
                            @endforelse
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">{{ __('demandes_confirmes.table.close_button') }}</button>
                        </div>
                    </div>
                </div>
            </div>

        @empty
            <p class="text-center fst-italic">{{ __('demandes_confirmes.no_demandes') }}</p>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $demandes->links() }}
    </div>
</div>
<style>
    /* CONTAINER */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
    font-family: 'Poppins', sans-serif;
    background: #f9f9f9;
}

/* GRID */
.row {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.col {
    flex: 1 1 calc(33.333% - 1rem);
}

/* MODERN COLORFUL CARD */
.demande-card {
    background: #ffffff;
    border-radius: 20px;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s, box-shadow 0.3s;
    border-top: 5px solid transparent;
}

/* CARD HOVER */
.demande-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
    border-top: 5px solid #5f9cff; /* nice gradient effect */
}

/* CARD TITLE */
.card-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
}

/* CARD TEXT */
.card p {
    font-size: 0.95rem;
    margin-bottom: 0.4rem;
    color: #555;
}

/* BADGE COLORFUL */
.badge {
    display: inline-block;
    padding: 0.25rem 0.7rem;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 600;
    color: #fff;
    background: linear-gradient(45deg, #5ca05e, #73a072);
    box-shadow: 0 4px 10px rgba(255, 126, 95, 0.3);
}

/* BUTTONS */
.demande-card .btn {
    border-radius: 12px;
    font-size: 0.9rem;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

/* PRIMARY BUTTON */
.btn-primary {
    background: linear-gradient(135deg, #4374ce, #185a9d);
    color: #fff;
    box-shadow: 0 4px 15px rgba(67, 206, 162, 0.4);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #185a9d, #4386ce);
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(67, 137, 206, 0.6);
}

/* OUTLINE BUTTON */
.btn-outline-primary {
    background: transparent;
    border: 2px solid #4376ce;
    color: #4389ce;
}

.btn-outline-primary:hover {
    background: rgba(67, 90, 206, 0.1);
    color: #18449d;
}

/* FLEX BUTTONS */
.mt-auto.d-flex {
    gap: 0.5rem;
}

/* MODAL GLOBAL STYLE */
.modal-content {
    border-radius: 20px;
    border: none;
    overflow: hidden;
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
    animation: modalFadeIn 0.4s ease;
    background: #ffffff;
}

/* HEADER */
.modal-header {
    border-bottom: none;
    padding: 1.2rem 1.5rem;
    background: linear-gradient(135deg, #4374ce, #185a9d);
    color: #fff;
}

.modal-header .modal-title {
    font-size: 1.1rem;
    font-weight: 600;
}

.modal-header .btn-close {
    filter: invert(1);
}

/* BODY */
.modal-body {
    padding: 1.5rem;
    font-size: 0.95rem;
    color: #444;
    background: #fdfdfd;
}

/* TEXTAREA INSIDE MODAL */
.modal-body textarea {
    border-radius: 12px;
    border: 1px solid #d1d5db;
    resize: none;
    transition: border-color 0.3s;
}
.modal-body textarea:focus {
    border-color: #4374ce;
    box-shadow: 0 0 0 3px rgba(67, 116, 206, 0.2);
}

/* FOOTER */
.modal-footer {
    border-top: none;
    padding: 1rem 1.5rem;
    background: #f8fafc;
}

/* MESSAGE HISTORY CARDS */
.message-card {
    background: #f9fafc;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    transition: transform 0.2s ease;
}
.message-card:hover {
    transform: scale(1.01);
    background: #f1f5f9;
}

/* ANIMATION */
@keyframes modalFadeIn {
    from {
        transform: translateY(-30px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* RESPONSIVE */
@media(max-width: 992px) {
    .col {
        flex: 1 1 calc(50% - 1rem);
    }
}

@media(max-width: 600px) {
    .col {
        flex: 1 1 100%;
    }
}

</style>
@endsection
