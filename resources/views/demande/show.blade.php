@extends('layouts.app')

@section('title', __('demande.detail_demande') . " #{$demande->id}")

@section('content')


<div class="container my-5 d-flex justify-content-center">
    <div class="card shadow-sm p-4" style="width: 900px;">
        <h2 class="mb-4 text-center">{{ __('demande.detail_demande') }}</h2>

        <div class="row mb-3">
            <div class="col-md-6"><strong>{{ __('demande.nom') }} :</strong> {{ $demande->nom }}</div>
            <div class="col-md-6"><strong>{{ __('demande.prenom') }} :</strong> {{ $demande->prenom }}</div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6"><strong>{{ __('demande.cin') }} :</strong> {{ $demande->cin }}</div>
            <div class="col-md-6"><strong>{{ __('demande.telephone') }} :</strong> {{ $demande->telephone }}</div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6"><strong>{{ __('demande.date_debut') }} :</strong> {{ optional($demande->date_debut)->format('d/m/Y') ?? __('demande.pas_defini') }}</div>
            <div class="col-md-6"><strong>{{ __('demande.date_fin') }} :</strong> {{ optional($demande->date_fin)->format('d/m/Y') ?? __('demande.pas_defini') }}</div>
        </div>

        <div class="mb-3">
            <strong>{{ __('demande.documents') }} :</strong>
          <ul>
    @foreach($demande->documents ?? [] as $doc)
        <li class="mb-2">
            <strong>{{ __('demande.categorie') }} :</strong>
            {{ __('documents.types.' . $doc['categorie']) ?? $doc['categorie'] }}<br>
            <strong>{{ __('demande.sous_type') }} :</strong>
            {{ __('documents.types.' . $doc['sous_type']) ?? $doc['sous_type'] }}
        </li>
    @endforeach
</ul>


        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <strong>{{ __('demande.prix_total') }} :</strong>
                @if(!is_null($demande->prix_total))
                    <span class="text-success">{{ number_format($demande->prix_total, 2, ',', ' ') }} MAD</span>
                @else
                    <span class="text-warning fst-italic">{{ __('demande.pas_defini') }}</span>
                @endif
            </div>
            <div class="col-md-6 d-flex align-items-center gap-2">
                <strong class="me-2">{{ __('demande.status') }} :</strong>

                @if(!$traduit)
                    <select id="statusSelect" class="form-select form-select-sm w-auto" data-id="{{ $demande->id }}">
                        <option value="en_cours" {{ $demande->status == 'en_cours' ? 'selected' : '' }}>{{ __('demande.en_cours') }}</option>
                        <option value="terminee" {{ $demande->status == 'terminee' ? 'selected' : '' }}>{{ __('demande.terminee') }}</option>
                        {{-- <option value="annulee" {{ $demande->status == 'annulee' ? 'selected' : '' }}>Annulée</option> --}}
                    </select>
                    <span id="statusMessage" class="text-success small ms-2"></span>
                @else
                    <p class="mb-0 badge bg-success">{{ ucfirst($demande->status) }}</p>
                @endif
            </div>
        </div>

        @if(!$traduit)
        <form action="{{ route('suivi_demande.uploadFiles', $demande->id) }}" method="POST" enctype="multipart/form-data"
              class="col-md-12 mt-3" id="uploadField" style="display:none;">
            @csrf
            <label for="justificatif" class="form-label">{{ __('demande.ajouter_fichiers') }}</label>
            <input type="file" class="form-control mb-2" name="justificatif_termine[]" id="justificatif" accept="application/pdf,image/*" multiple required>
            <button type="submit" class="btn btn-success btn-sm mt-2">📤 {{ __('demande.envoyer_fichiers') }}</button>
        </form>
        @endif

        <div class="row mb-3 mt-4">
            <div class="col-md-6"><strong>{{ __('demande.langue_origine') }} :</strong> {{ $demande->langue_origine }}</div>
            <div class="col-md-6"><strong>{{ __('demande.langue_souhaitee') }} :</strong> {{ $demande->langue_souhaitee }}</div>
        </div>

        <div class="mb-4">
            <strong>{{ __('demande.remarque') }} :</strong>
            <p class="border rounded p-3 bg-light">{{ $demande->remarque ?? __('demande.aucune_remarque') }}</p>
        </div>

        <div class="mb-4">
            <strong>{{ __('demande.fichiers_justificatifs') }} :</strong>

            @php
                $fichiers_initial = $demande->fichiers->where('type', 'initial');
                $fichiers_final = $demande->fichiers->where('type', 'final');
            @endphp

            <div class="mb-3">
                <h6>📄 {{ __('demande.documents_avant') }} :</h6>
                @if($fichiers_initial->count() > 0)
                    <ul class="list-group">
                        @foreach($fichiers_initial as $fichier)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="flex-grow-1">{{ basename($fichier->chemin) }}</span>
                                <div class="d-flex gap-2">
                                    <a href="{{ asset('storage/' . $fichier->chemin) }}" target="_blank" class="btn btn-sm btn-info">{{ __('demande.voir') ?? 'Voir' }}</a>
                                    <a href="{{ route('suivi_demande.download', ['id' => $demande->id, 'fichierId' => $fichier->id]) }}" class="btn btn-sm btn-primary">{{ __('demande.telecharger') ?? 'Télécharger' }}</a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="fst-italic text-muted">{{ __('demande.aucun_fichier_disponible') }}</p>
                @endif
            </div>

            <div>
                <h6>✅ {{ __('demande.documents_apres') }} :</h6>
                @if($fichiers_final->count() > 0)
                    <ul class="list-group">
                        @foreach($fichiers_final as $fichier)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="flex-grow-1">{{ basename($fichier->chemin) }}</span>
                                <div class="d-flex gap-2">
                                    <a href="{{ asset('storage/' . $fichier->chemin) }}" target="_blank" class="btn btn-sm btn-info">{{ __('demande.voir') ?? 'Voir' }}</a>
                                    <a href="{{ route('suivi_demande.download', ['id' => $demande->id, 'fichierId' => $fichier->id]) }}" class="btn btn-sm btn-primary">{{ __('demande.telecharger') ?? 'Télécharger' }}</a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="fst-italic text-muted">{{ __('demande.aucun_fichier_disponible') }}</p>
                @endif
            </div>
        </div>

        <div class="text-center">
            @if(!$traduit)
                <a href="{{ route('suivi_demande.index') }}" class="btn btn-outline-secondary">{{ __('demande.retour_liste') }}</a>
            @else
                <a href="{{ route('suivi_demande.index2') }}" class="btn btn-outline-secondary">{{ __('demande.retour_liste') }}</a>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('statusSelect');
    const message = document.getElementById('statusMessage');
    const uploadField = document.getElementById('uploadField');

    function toggleUploadField(status) {
        uploadField.style.display = (status === 'terminee') ? 'block' : 'none';
        const existingInput = uploadField.querySelector('input[name="status"]');
        if (status === 'terminee') {
            if (!existingInput) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'status';
                input.value = 'terminee';
                uploadField.appendChild(input);
            } else {
                existingInput.value = 'terminee';
            }
        } else if (existingInput) {
            existingInput.remove();
        }
    }

    toggleUploadField(select.value);

    select.addEventListener('change', function () {
        const newStatus = this.value;
        const demandeId = this.dataset.id;

        toggleUploadField(newStatus);

        fetch(`/suivi_demande/${demandeId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            message.textContent = '✅ ' + (data.message ?? '{{ __("demande.status_updated") }}');
            setTimeout(() => message.textContent = '', 2000);
        })
        .catch(error => {
            console.error('Erreur:', error);
            message.textContent = '❌ ' + (error.message ?? '{{ __("demande.status_update_error") }}');
        });
    });
});
</script>
@endsection
