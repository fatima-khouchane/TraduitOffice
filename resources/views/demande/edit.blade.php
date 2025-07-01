@extends('layouts.app')

@section('title', 'Modifier la Demande')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-10 offset-md-2 d-flex justify-content-center">
      <div class="card shadow p-4 my-4" style="width: 100%; max-width: 900px;">
        <h2 class="mb-4 text-center">Modifier la demande</h2>

        @if (session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('suivi_demande.update', $demande->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <!-- Nom / Prénom -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Nom</label>
              <input type="text" name="nom" class="form-control" required value="{{ old('nom', $demande->nom) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">Prénom</label>
              <input type="text" name="prenom" class="form-control" required value="{{ old('prenom', $demande->prenom) }}">
            </div>
          </div>

          <!-- CIN / Téléphone -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">N° Carte Nationale</label>
              <input type="text" name="cin" class="form-control" required value="{{ old('cin', $demande->cin) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">Téléphone</label>
              <input type="text" name="telephone" class="form-control" required value="{{ old('telephone', $demande->telephone) }}">
            </div>
          </div>

          <!-- Dates -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Date début</label>
              <input type="date" name="date_debut" class="form-control" required value="{{ old('date_debut', $demande->date_debut->format('Y-m-d')) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">Date fin</label>
              <input type="date" name="date_fin" class="form-control" required value="{{ old('date_fin', $demande->date_fin->format('Y-m-d')) }}">
            </div>
          </div>

          <!-- Documents -->
          <div id="documents_container">
            @foreach(old('categorie', $demande->documents ? array_column($demande->documents, 'categorie') : []) as $index => $cat)
              <div class="document_group mb-3 row align-items-end">
                <div class="col-md-6">
                  <label class="form-label">Catégorie de document</label>
                  <select name="categorie[]" class="form-control categorie_select" required>
                    <option value="">-- Sélectionner une catégorie --</option>
                    <option value="administratif" {{ $cat == 'administratif' ? 'selected' : '' }}>📌 1. PIÈCES ADMINISTRATIVES ORDINAIRES / PAGE</option>
                    <option value="medical" {{ $cat == 'medical' ? 'selected' : '' }}>🩺 2. CERTIFICATS MÉDICAUX / PAGE</option>
                    <option value="notaire" {{ $cat == 'notaire' ? 'selected' : '' }}>📄 3. PIÈCES NOTARIÉES OU ADMINISTRATIVES / PAGE</option>
                    <option value="etranger" {{ $cat == 'etranger' ? 'selected' : '' }}>🌍 4. PIÈCES ÉTABLIES À L’ÉTRANGER / PAGE</option>
                    <option value="dossier" {{ $cat == 'dossier' ? 'selected' : '' }}>📚 5. DOSSIERS (2 à 10 pages, 240 mots / page)</option>
                    <option value="interprete" {{ $cat == 'interprete' ? 'selected' : '' }}>🎤 6. INTERPRÉTARIAT (SIMULTANÉ OU CONSÉCUTIF)</option>
                    <option value="douane" {{ $cat == 'douane' ? 'selected' : '' }}>📦 7. PIÈCES DOUANIÈRES / PAGE</option>
                  </select>
                  <input type="hidden" name="categorie_label[]" class="categorie_label" value="">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Sous-type de document</label>
                  <select name="sous_type[]" class="form-control sous_type_select" required style="display:none;">
                    @php
                      $sousTypes = [
                        "administratif" => [ "Certificat de résidence", "Attestation de célibat", "Extrait d’acte de naissance", "Copie intégrale d’acte de naissance", "Extrait du casier judiciaire", "Attestation de travail", "Attestation de salaire", "Attestation de prise en charge", "Certificat de scolarité", "Certificat de radiation", "Certificat de non-inscription au commerce", "Certificat de capacité d’inscription", "Bulletin de notes (système Bac marocain)", "Bulletin de notes (système scolaire étranger)", "Copies supplémentaires / page" ],
                        "medical" => [ "Certificat médical", "Dossier médical" ],
                        "notaire" => [ "Acte de mariage", "Acte notarié", "Acte de naissance", "Jugement de divorce", "Jugement étranger exequatur", "Jugement d’adoption", "Acte de tutelle", "Acte de kafala", "Décision d’expulsion", "Jugement étranger avant 2004", "Jugement étranger après la famille 2004", "Jugement étranger divers (divorces irrévocables)", "Acte de répudiation", "Acte de désistement", "Kafala", "Acte de prise en charge", "Procuration", "Remise d’enfants", "Acte d’hérédité", "Inventaire successoral", "Partage successoral", "Acte d’achat / vente", "Copies supplémentaires / page" ],
                        "etranger" => [ "Copie intégrale d’acte de naissance", "Acte de naissance", "Certificat de capacité à mariage", "Certificat de divorce", "Extrait du casier judiciaire", "Attestation de célibat", "Certificat de résidence", "Acte de mariage" ],
                        "dossier" => [ "Jugements", "Actes notariés", "Procès-verbaux", "Statuts", "Dossiers techniques" ],
                        "interprete" => [ "Assemblées générales", "Conseils d’administration", "Séances de délibérations", "Débats aux audiences des tribunaux" ],
                        "douane" => [ "Déclaration en douane", "Facture commerciale", "Certificat d’origine", "Liste de colisage", "Document de transport" ]
                      ];
                      $catSousTypes = $sousTypes[$cat] ?? [];
                      $selectedSousType = old('sous_type', array_column($demande->documents ?? [], 'sous_type'))[$index] ?? '';
                    @endphp
                    @foreach($catSousTypes as $st)
                      <option value="{{ $st }}" {{ $st == $selectedSousType ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            @endforeach
          </div>

          <button type="button" id="add_document" class="btn btn-secondary mb-3">+ Ajouter un autre document</button>

          <!-- Prix + Langues -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Prix total</label>
              <input type="number" step="0.01" name="prix_total" class="form-control" required value="{{ old('prix_total', $demande->prix_total) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">Langue d'origine</label>
              <select name="langue_origine" class="form-control" required>
                <option value="">-- Sélectionner une langue --</option>
                <option value="Anglais" {{ old('langue_origine', $demande->langue_origine) == 'Anglais' ? 'selected' : '' }}>Anglais</option>
                <option value="Arabe" {{ old('langue_origine', $demande->langue_origine) == 'Arabe' ? 'selected' : '' }}>Arabe</option>
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Langue souhaitée</label>
              <select name="langue_souhaitee" class="form-control" required>
                <option value="">-- Sélectionner une langue --</option>
                <option value="Anglais" {{ old('langue_souhaitee', $demande->langue_souhaitee) == 'Anglais' ? 'selected' : '' }}>Anglais</option>
                <option value="Arabe" {{ old('langue_souhaitee', $demande->langue_souhaitee) == 'Arabe' ? 'selected' : '' }}>Arabe</option>
              </select>
            </div>
          </div>
          <div class="mb-3">
            <label for="status" class="form-label">Statut de la demande</label>
            <select name="status" id="status" class="form-control" required>
              <option value="en_cours" {{ old('status', $demande->status) === 'en_cours' ? 'selected' : '' }}>En cours</option>
              <option value="terminee" {{ old('status', $demande->status) === 'terminee' ? 'selected' : '' }}>Terminée</option>
              <option value="annulee" {{ old('status', $demande->status) === 'annulee' ? 'selected' : '' }}>Annulée</option>
            </select>
          </div>

          <!-- Remarque -->
          <div class="mb-3">
            <label class="form-label">Remarque (optionnel)</label>
            <textarea name="remarque" rows="3" class="form-control">{{ old('remarque', $demande->remarque) }}</textarea>
          </div>

          <!-- Fichiers existants -->
          <div class="mb-3">
            <label class="form-label">Fichiers justificatifs existants</label>
            <ul>
                @foreach($demande->fichiers as $fichier)
                  <li>
                    <a href="{{ asset('storage/' . $fichier->chemin) }}" target="_blank">Voir</a>
                    <label>
                      <input type="checkbox" name="supprimer_fichiers[]" value="{{ $fichier->id }}" class="delete-checkbox">
                      Supprimer ce fichier
                    </label>
                  </li>
                @endforeach
              </ul>
          </div>

          <!-- Ajouter de nouveaux fichiers -->
          <div id="fichiers_container">
            <div class="mb-3">
              <label class="form-label">Ajouter un nouveau fichier justificatif</label>
              <input type="file" name="fichiers[]" class="form-control" accept="application/pdf,image/*">
            </div>
          </div>
          <button type="button" class="btn btn-secondary" id="add_file_btn">+ Ajouter un autre fichier</button>

          <!-- Submit -->
          <button type="submit" class="btn btn-primary">Mettre à jour la demande</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  // Copie du script pour gérer sous-type et ajout dynamique

  const sousTypes = {
    administratif: [ "Certificat de résidence", "Attestation de célibat", "Extrait d’acte de naissance", "Copie intégrale d’acte de naissance", "Extrait du casier judiciaire", "Attestation de travail", "Attestation de salaire", "Attestation de prise en charge", "Certificat de scolarité", "Certificat de radiation", "Certificat de non-inscription au commerce", "Certificat de capacité d’inscription", "Bulletin de notes (système Bac marocain)", "Bulletin de notes (système scolaire étranger)", "Copies supplémentaires / page" ],
    medical: [ "Certificat médical", "Dossier médical" ],
    notaire: [ "Acte de mariage", "Acte notarié", "Acte de naissance", "Jugement de divorce", "Jugement étranger exequatur", "Jugement d’adoption", "Acte de tutelle", "Acte de kafala", "Décision d’expulsion", "Jugement étranger avant 2004", "Jugement étranger après la famille 2004", "Jugement étranger divers (divorces irrévocables)", "Acte de répudiation", "Acte de désistement", "Kafala", "Acte de prise en charge", "Procuration", "Remise d’enfants", "Acte d’hérédité", "Inventaire successoral", "Partage successoral", "Acte d’achat / vente", "Copies supplémentaires / page" ],
    etranger: [ "Copie intégrale d’acte de naissance", "Acte de naissance", "Certificat de capacité à mariage", "Certificat de divorce", "Extrait du casier judiciaire", "Attestation de célibat", "Certificat de résidence", "Acte de mariage" ],
    dossier: [ "Jugements", "Actes notariés", "Procès-verbaux", "Statuts", "Dossiers techniques" ],
    interprete: [ "Assemblées générales", "Conseils d’administration", "Séances de délibérations", "Débats aux audiences des tribunaux" ],
    douane: [ "Déclaration en douane", "Facture commerciale", "Certificat d’origine", "Liste de colisage", "Document de transport" ]
  };

  function updateSousType(select) {
    const group = select.closest('.document_group');
    const sousSelect = group.querySelector('.sous_type_select');
    const labelInput = group.querySelector('.categorie_label');

    sousSelect.innerHTML = '';
    sousSelect.style.display = 'none';

    const type = select.value;
    const label = select.options[select.selectedIndex].text;
    labelInput.value = label;

    if (sousTypes[type]) {
      sousTypes[type].forEach(item => {
        const opt = document.createElement('option');
        opt.value = item;
        opt.textContent = item;
        sousSelect.appendChild(opt);
      });
      sousSelect.style.display = 'block';
    }
  }

  // Initialiser les selects au chargement
  document.querySelectorAll('.categorie_select').forEach(select => {
    select.addEventListener('change', function () {
      updateSousType(this);
    });

    if (select.value !== '') {
      updateSousType(select);
    }
  });

  // Ajouter un nouveau bloc document
 // Ajouter un nouveau bloc document
document.getElementById('add_document').addEventListener('click', () => {
  const container = document.getElementById('documents_container');
  const group = container.querySelector('.document_group');
  const clone = group.cloneNode(true);

  const newCategorie = clone.querySelector('.categorie_select');
  const newLabel = clone.querySelector('.categorie_label');
  const newSousType = clone.querySelector('.sous_type_select');

  newCategorie.value = '';
  newLabel.value = '';
  newSousType.innerHTML = '';
  newSousType.style.display = 'none';

  newCategorie.addEventListener('change', function () {
    updateSousType(this);
  });

  // Bouton Supprimer
  const cancelBtn = document.createElement('button');
  cancelBtn.type = 'button';
  cancelBtn.classList.add('btn', 'btn-outline-danger', 'btn-sm', 'mt-2');
  cancelBtn.textContent = '✖ Supprimer';
  cancelBtn.addEventListener('click', () => {
    clone.remove();
  });

  clone.appendChild(cancelBtn);
  container.appendChild(clone);
});

  // Ajouter un nouveau fichier
 // Ajouter un nouveau fichier
document.getElementById('add_file_btn').addEventListener('click', function () {
  const container = document.getElementById('fichiers_container');

  const wrapper = document.createElement('div');
  wrapper.classList.add('d-flex', 'align-items-center', 'gap-2', 'mt-2');

  const input = document.createElement('input');
  input.type = 'file';
  input.name = 'fichiers[]';
  input.classList.add('form-control');
  input.accept = 'application/pdf,image/*';

  const cancelBtn = document.createElement('button');
  cancelBtn.type = 'button';
  cancelBtn.classList.add('btn', 'btn-outline-danger', 'btn-sm');
  cancelBtn.textContent = '✖';

  cancelBtn.addEventListener('click', () => {
    wrapper.remove();
  });

  wrapper.appendChild(input);
  wrapper.appendChild(cancelBtn);
  container.appendChild(wrapper);
});

  document.getElementById('deleteFichiersForm').addEventListener('submit', function (e) {
    const checkboxes = document.querySelectorAll('.delete-checkbox:checked');

    if (checkboxes.length === 0) {
      e.preventDefault();
      alert("❌ Veuillez sélectionner au moins un fichier à supprimer.");
    } else {
      const confirmDelete = confirm("⚠️ Voulez-vous vraiment supprimer les fichiers sélectionnés ?");
      if (!confirmDelete) {
        e.preventDefault();
      }
    }
  });
</script>
@endsection
