@extends('layouts.app')

@section('title', __('demandes.edit_title'))

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-10 offset-md-2 d-flex justify-content-center">
      <div class="card shadow p-4 my-4" style="width: 100%; max-width: 900px;">
        <h2 class="mb-4 text-center">{{ __('demandes.edit_heading') }}</h2>

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
              <label class="form-label">{{ __('demandes.nom_demandeur') }}</label>
              <input type="text" name="nom_demandeur" class="form-control" required value="{{ old('nom_demandeur', $demande->nom_demandeur) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">{{ __('demandes.nom_titulaire') }}</label>
              <input type="text" name="nom_titulaire" class="form-control" required value="{{ old('nom_titulaire', $demande->nom_titulaire) }}">
            </div>
          </div>

          <!-- CIN / Téléphone -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">{{ __('demandes.cin') }}</label>
              <input type="text" name="cin" class="form-control" required value="{{ old('cin', $demande->cin) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">{{ __('demandes.telephone') }}</label>
              <input type="text" name="telephone" class="form-control" required value="{{ old('telephone', $demande->telephone) }}">
            </div>
          </div>

          <!-- Dates -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">{{ __('demandes.date_debut') }}</label>
              <input type="date" name="date_debut" class="form-control" required value="{{ old('date_debut', $demande->date_debut ? $demande->date_debut->format('Y-m-d') : '') }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">{{ __('demandes.date_fin') }}</label>
              <input type="date" name="date_fin" class="form-control" required value="{{ old('date_fin', $demande->date_fin ? $demande->date_fin->format('Y-m-d') : '') }}">
            </div>
          </div>

          <!-- Documents dynamiques -->
          <div id="documents_container">
            @foreach(old('categorie', $demande->documents ? array_column($demande->documents, 'categorie') : []) as $index => $oldCategorie)
              @php
                $oldSousType = old('sous_type')[$index] ?? ($demande->documents[$index]['sous_type'] ?? '');
              @endphp
              <div class="document_group mb-3 row align-items-end">
                <div class="col-md-6">
                  <label class="form-label">{{ __('demandes.categorie_document') }}</label>
                  <select name="categorie[]" class="form-control categorie_select" required>
                    <option value="">{{ __('demandes.selectionner_categorie') }}</option>
                    @foreach(array_keys($documents) as $categorie)
                      <option value="{{ $categorie }}" {{ $categorie === $oldCategorie ? 'selected' : '' }}>
                        {{ __('documents.types.' . $categorie) }}
                      </option>
                    @endforeach
                  </select>
                  <input type="hidden" name="categorie_label[]" class="categorie_label" value="{{ $oldCategorie }}">
                </div>
                <div class="col-md-6">
                  <label class="form-label">{{ __('demandes.sous_type_document') }}</label>
                  <select name="sous_type[]" class="form-control sous_type_select" required>
                    @if(isset($documents[$oldCategorie]))
                      @foreach($documents[$oldCategorie] as $sousType)
                        <option value="{{ $sousType }}" {{ $sousType === $oldSousType ? 'selected' : '' }}>
                          {{ __('documents.types.' . $sousType) }}
                        </option>
                      @endforeach
                    @endif
                  </select>
                </div>
              </div>
            @endforeach
          </div>

          <button type="button" id="add_document" class="btn btn-secondary mb-3">
            + {{ __('demandes.ajouter_document') }}
          </button>

          <!-- Prix + Langues -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">{{ __('demandes.prix_total') }}</label>
              <input type="number" step="0.01" name="prix_total" class="form-control" required value="{{ old('prix_total', $demande->prix_total) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">{{ __('demandes.langue_origine') }}</label>
              <select name="langue_origine" class="form-control" required>
                <option value="">{{ __('demandes.select_language') }}</option>
                <option value="Anglais" {{ old('langue_origine', $demande->langue_origine) == 'Anglais' ? 'selected' : '' }}>{{ __('demandes.anglais') }}</option>
                <option value="Arabe" {{ old('langue_origine', $demande->langue_origine) == 'Arabe' ? 'selected' : '' }}>{{ __('demandes.arabe') }}</option>
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">{{ __('demandes.langue_souhaitee') }}</label>
              <select name="langue_souhaitee" class="form-control" required>
                <option value="">{{ __('demandes.select_language') }}</option>
                <option value="Anglais" {{ old('langue_souhaitee', $demande->langue_souhaitee) == 'Anglais' ? 'selected' : '' }}>{{ __('demandes.anglais') }}</option>
                <option value="Arabe" {{ old('langue_souhaitee', $demande->langue_souhaitee) == 'Arabe' ? 'selected' : '' }}>{{ __('demandes.arabe') }}</option>
              </select>
            </div>
          </div>

          <!-- Statut -->
          <div class="mb-3">
            <label for="status" class="form-label">{{ __('demandes.status') }}</label>
            <select name="status" id="status" class="form-control" required>
              <option value="en_cours" {{ old('status', $demande->status) === 'en_cours' ? 'selected' : '' }}>{{ __('demandes.status_en_cours') }}</option>
              <option value="en_attente" {{ old('status', $demande->status) === 'en_attente' ? 'selected' : '' }}>{{ __('demandes.status_en_attente') }}</option>
            </select>
          </div>

          <!-- Remarque -->
          <div class="mb-3">
            <label class="form-label">{{ __('demandes.remarque') }}</label>
            <textarea name="remarque" rows="3" class="form-control">{{ old('remarque', $demande->remarque) }}</textarea>
          </div>

          <!-- Fichiers existants -->
          <div class="mb-3">
            <label class="form-label">{{ __('demandes.fichiers_existants') }}</label>
            <ul>
              @foreach($demande->fichiers as $fichier)
                <li>
                  <a href="{{ asset('storage/' . $fichier->chemin) }}" target="_blank">{{ __('demandes.voir') }}</a>
                  <label class="ms-2">
                    <input type="checkbox" name="supprimer_fichiers[]" value="{{ $fichier->id }}" class="delete-checkbox">
                    {{ __('demandes.supprimer_fichier') }}
                  </label>
                </li>
              @endforeach
            </ul>
          </div>

          <!-- Ajouter fichiers -->
          <div id="fichiers_container">
            <div class="mb-3">
              <label class="form-label">{{ __('demandes.ajouter_fichier') }}</label>
              <input type="file" name="fichiers[]" class="form-control" accept="application/pdf,image/*">
            </div>
          </div>

          <button type="button" class="btn btn-secondary" id="add_file_btn">+ {{ __('demandes.ajouter_autre_fichier') }}</button>

          <!-- Submit -->
          <button type="submit" class="btn btn-primary mt-3">{{ __('demandes.mettre_a_jour') }}</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  const translations = @json(trans('documents.types'));
  const sousTypes = @json($documents);

  function translate(key) {
    return translations[key] || key;
  }

  function updateSousType(select) {
    const group = select.closest('.document_group');
    const sousSelect = group.querySelector('.sous_type_select');
    const labelInput = group.querySelector('.categorie_label');

    sousSelect.innerHTML = '';
    sousSelect.style.display = 'none';

    const type = select.value;
    labelInput.value = type;

    if (sousTypes[type]) {
      sousTypes[type].forEach(itemKey => {
        const opt = document.createElement('option');
        opt.value = itemKey;
        opt.textContent = translate(itemKey);
        sousSelect.appendChild(opt);
      });
      sousSelect.style.display = 'block';
    }
  }

  document.querySelectorAll('.categorie_select').forEach(select => {
    select.addEventListener('change', function () {
      updateSousType(this);
    });
  });

  document.getElementById('add_document').addEventListener('click', () => {
    const container = document.getElementById('documents_container');
    const original = container.querySelector('.document_group');
    const clone = original.cloneNode(true);

    const categorieSelect = clone.querySelector('.categorie_select');
    const labelInput = clone.querySelector('.categorie_label');
    const sousSelect = clone.querySelector('.sous_type_select');

    categorieSelect.value = '';
    labelInput.value = '';
    sousSelect.innerHTML = '';
    sousSelect.style.display = 'none';

    categorieSelect.addEventListener('change', function () {
      updateSousType(this);
    });

    const cancelBtn = document.createElement('button');
    cancelBtn.type = 'button';
    cancelBtn.classList.add('btn', 'btn-outline-danger', 'btn-sm', 'mt-2');
    cancelBtn.innerText = '✖ Supprimer';
    cancelBtn.addEventListener('click', () => {
      clone.remove();
    });

    clone.appendChild(cancelBtn);
    container.appendChild(clone);
  });

  document.getElementById('add_file_btn').addEventListener('click', () => {
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
    cancelBtn.innerText = '✖';

    cancelBtn.addEventListener('click', () => {
      wrapper.remove();
    });

    wrapper.appendChild(input);
    wrapper.appendChild(cancelBtn);
    container.appendChild(wrapper);
  });
</script>
@endsection
