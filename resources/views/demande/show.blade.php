@extends('layouts.app')

@section('title', "Détail de la demande #{$demande->id}")

@section('content')
@php
    $categorieLabels = [
        'administratif' => '📌 1. PIÈCES ADMINISTRATIVES ORDINAIRES / PAGE',
        'medical'       => '🩺 2. CERTIFICATS MÉDICAUX / PAGE',
        'notaire'       => '📄 3. PIÈCES NOTARIÉES OU ADMINISTRATIVES / PAGE',
        'etranger'      => '🌍 4. PIÈCES ÉTABLIES À L’ÉTRANGER / PAGE',
        'dossier'       => '📚 5. DOSSIERS (2 à 10 pages, 240 mots / page)',
        'interprete'    => '🎤 6. INTERPRÉTARIAT (SIMULTANÉ OU CONSÉCUTIF)',
        'douane'        => '📦 7. PIÈCES DOUANIÈRES / PAGE',
    ];
@endphp

<div class="container my-5 justify-content-center">
    <div class="card shadow-sm p-4" style="width: 900px; margin:0 auto;">
        <h2 class="mb-4 text-center">Détail de la demande</h2>

        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Nom :</strong> {{ $demande->nom }}
            </div>
            <div class="col-md-6">
                <strong>Prénom :</strong> {{ $demande->prenom }}
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <strong>CIN :</strong> {{ $demande->cin }}
            </div>
            <div class="col-md-6">
                <strong>Téléphone :</strong> {{ $demande->telephone }}
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Date début :</strong> {{ $demande->date_debut->format('d/m/Y') }}
            </div>
            <div class="col-md-6">
                <strong>Date fin :</strong> {{ $demande->date_fin->format('d/m/Y') }}
            </div>
        </div>

        <div class="mb-3">
            <strong>Documents :</strong>
            <ul>
                @foreach($demande->documents ?? [] as $doc)
                    <li class="mb-2">
                        <strong>Catégorie :</strong>
                        {{ $categorieLabels[$doc['categorie'] ?? ''] ?? ucfirst($doc['categorie'] ?? 'Non spécifiée') }}<br>
                        <strong>Sous-type :</strong> {{ $doc['sous_type'] ?? '—' }}
                    </li>
                @endforeach
            </ul>

        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Prix total :</strong> {{ number_format($demande->prix_total, 2, ',', ' ') }} MAD
            </div>
            <div class="col-md-6 d-flex align-items-center gap-2">
                <strong class="me-2">Status :</strong>

                @if(!$traduit)
                    <select id="statusSelect" class="form-select form-select-sm w-auto"
                            data-id="{{ $demande->id }}">
                        <option value="en_cours" {{ $demande->status == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="terminee" {{ $demande->status == 'terminee' ? 'selected' : '' }}>Terminée</option>
                        <option value="annulee" {{ $demande->status == 'annulee' ? 'selected' : '' }}>Annulée</option>
                    </select>
                    <span id="statusMessage" class="text-success small ms-2"></span>
                @else
                    <p class="mb-0 badge bg-success">{{ ucfirst($demande->status) }}</p>
                @endif
            </div>


            @if(!$traduit)
            <form action="{{ route('suivi_demande.uploadFiles', $demande->id) }}" method="POST" enctype="multipart/form-data"
                class="col-md-12 mt-3" id="uploadField" style="display:none;">
                @csrf
                <label for="justificatif" class="form-label">Ajouter un ou plusieurs fichiers (PDF / Image)</label>
                <input type="file"
                       class="form-control mb-2"
                       name="justificatif_termine[]"
                       id="justificatif"
                       accept="application/pdf,image/*"
                       multiple required>
                <button type="submit" class="btn btn-success btn-sm mt-2">📤 Envoyer les fichiers</button>
            </form>
        @endif




        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Langue d'origine :</strong> {{ $demande->langue_origine }}
            </div>
            <div class="col-md-6">
                <strong>Langue souhaitée :</strong> {{ $demande->langue_souhaitee }}
            </div>
        </div>

        <div class="mb-4">
            <strong>Remarque :</strong>
            <p class="border rounded p-3 bg-light">{{ $demande->remarque ?? 'Aucune remarque' }}</p>
        </div>

        <div class="mb-4">
            <strong>Fichiers justificatifs :</strong>

            @php
                $fichiers_initial = $demande->fichiers->where('type', 'initial');
                $fichiers_final = $demande->fichiers->where('type', 'final');
            @endphp

            <div class="mb-3">
                <h6>📄 Documents AVANT traduction :</h6>
                @if($fichiers_initial->count() > 0)
                <ul class="list-group">
                    @foreach($fichiers_initial as $fichier)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="flex-grow-1">{{ basename($fichier->chemin) }}</span>
                            <div class="d-flex gap-2">
                                <a href="{{ asset('storage/' . $fichier->chemin) }}" target="_blank" class="btn btn-sm btn-info">Voir</a>
                                <a href="{{ route('suivi_demande.download', ['id' => $demande->id, 'fichierId' => $fichier->id]) }}" class="btn btn-sm btn-primary">Télécharger</a>
                            </div>
                        </li>
                    @endforeach
                </ul>
                @else
                    <p class="fst-italic text-muted">Aucun fichier initial disponible.</p>
                @endif
            </div>

            <div>
                <h6>✅ Documents APRÈS traduction :</h6>
                @if($fichiers_final->count() > 0)
                <ul class="list-group">
                    @foreach($fichiers_final as $fichier)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="flex-grow-1">{{ basename($fichier->chemin) }}</span>
                            <div class="d-flex gap-2">
                                <a href="{{ asset('storage/' . $fichier->chemin) }}" target="_blank" class="btn btn-sm btn-info">Voir</a>
                                <a href="{{ route('suivi_demande.download', ['id' => $demande->id, 'fichierId' => $fichier->id]) }}" class="btn btn-sm btn-primary">Télécharger</a>
                            </div>
                        </li>
                    @endforeach
                </ul>
                @else
                    <p class="fst-italic text-muted">Aucun fichier final disponible.</p>
                @endif
            </div>
        </div>


        <div class="text-center">
            <a href="{{ route('suivi_demande.index') }}" class="btn btn-outline-secondary">
                ← Retour à la liste
            </a>
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
        // Si visible, ajoute un champ hidden status
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
            message.textContent = '✅ Statut mis à jour';
            setTimeout(() => message.textContent = '', 2000);
        })
        .catch(error => {
            console.error('Erreur:', error);
            message.textContent = '❌ Erreur lors de la mise à jour';
        });
    });
});

</script>
    @endsection
