@extends('layouts.client')

@section('title', __('demandeClient.envoyer_demande'))

@section('content')
<div class="container-fluid">

  <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="col-md-10 d-flex justify-content-center">
      <div class="card shadow p-4 my-4" style="width: 100%; max-width: 900px;">
        <h2 class="mb-4 text-center">{{ __('demandeClient.envoyer_demande') }}</h2>

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

        <form action="{{ route('demande.store_client') }}" method="POST" enctype="multipart/form-data">
          @csrf

          <!-- Infos utilisateur -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">{{ __('demandeClient.nom_complet') }}</label>
              <input type="text" name="nom_titulaire" class="form-control" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">{{ __('demandeClient.adresse') }}</label>
              <textarea name="adresse" class="form-control" rows="3" required></textarea>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">{{ __('demandeClient.cin') }}</label>
              <input type="text" name="cin" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">{{ __('demandeClient.telephone') }}</label>
              <input type="text" name="telephone" class="form-control" required>
            </div>
          </div>

          <!-- Documents dynamiques -->
          <div id="documents_container">
            <div class="document_group mb-3 row align-items-end">
              <div class="col-md-6">
                <label class="form-label">{{ __('demande.categorie_document') }}</label>
                <select name="categorie[]" class="form-control categorie_select" required>
                  <option value="">{{ __('demande.selectionner_categorie') }}</option>
                  @foreach(array_keys($documents) as $categorie)
                    <option value="{{ $categorie }}">{{ __('documents.types.' . $categorie) }}</option>
                  @endforeach
                </select>
                <input type="hidden" name="categorie_label[]" class="categorie_label" value="">
              </div>
              <div class="col-md-6">
                <label class="form-label">{{ __('demande.sous_type_document') }}</label>
                <select name="sous_type[]" class="form-control sous_type_select" required style="display:none;"></select>
              </div>
            </div>
          </div>

          <button type="button" id="add_document" class="btn btn-secondary mb-3">
            + {{ __('demandeClient.ajouter_document') }}
          </button>

          <!-- Langues -->
          <div class="row mb-3">
           <div class="col-md-6">
              <label class="form-label">{{ __('demande.langue_origine') }}</label>
              <select name="langue_origine" class="form-control" required>
                <option value="">{{ __('demande.selectionner_langue') }}</option>
                <option value="Anglais">{{ __('demande.anglais') }}</option>
                <option value="Arabe">{{ __('demande.arabe') }}</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">{{ __('demandeClient.langue_souhaitee') }}</label>
              <select name="langue_souhaitee" class="form-select" required>
                <option value="">{{ __('demandeClient.select_lang') }}</option>
                <option value="Anglais">{{ __('demandeClient.anglais') }}</option>
                <option value="Arabe">{{ __('demandeClient.arabe') }}</option>
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">{{ __('demandeClient.remarque') }}</label>
            <textarea name="remarque" rows="3" class="form-control"></textarea>
          </div>

          <!-- Fichiers justificatifs -->
          <div id="fichiers_container">
            <div class="mb-3 d-flex align-items-center gap-2">
              <label class="form-label w-100">{{ __('demandeClient.fichier_justificatif') }}</label>
              <input type="file" name="fichiers[]" class="form-control" accept="application/pdf,image/*" required>
            </div>
          </div>

          <button type="button" class="btn btn-secondary " id="add_file_btn">
            + {{ __('demandeClient.ajouter_fichier') }}
          </button>
          <button type="submit" class="btn btn-primary ">{{ __('demandeClient.envoyer') }}</button>

        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')

<script>

    const allTranslations = {
    'fr': @json(__('documents.types')),
    'fr_sous': @json(__('documents.sous_types')),
    'en': @json(__('documents.types', [], 'en')),
    'en_sous': @json(__('documents.sous_types', [], 'en')),
    'ar': @json(__('documents.types', [], 'ar')),
    'ar_sous': @json(__('documents.sous_types', [], 'ar')),
};

let currentLang = '{{ app()->getLocale() }}';

function translate(key, type = null) {
    if (type && allTranslations[currentLang + '_sous'][type] && allTranslations[currentLang + '_sous'][type][key]) {
        return allTranslations[currentLang + '_sous'][type][key];
    }
    return allTranslations[currentLang][key] || key;
}

function updateSousType(select) {
    const group = select.closest('.document_group');
    const sousSelect = group.querySelector('.sous_type_select');
    const labelInput = group.querySelector('.categorie_label');

    sousSelect.innerHTML = '';
    sousSelect.style.display = 'none';

    const type = select.value;
    labelInput.value = type;

    if (allTranslations[currentLang + '_sous'][type]) {
        Object.keys(allTranslations[currentLang + '_sous'][type]).forEach(key => {
            const opt = document.createElement('option');
            opt.value = key; // conserver la clé comme valeur
            opt.textContent = translate(key, type); // traduction selon langue
            sousSelect.appendChild(opt);
        });
        sousSelect.style.display = 'block';
    }
}

// Attacher le listener aux selects existants
document.querySelectorAll('.categorie_select').forEach(select => {
    updateSousType(select); // remplissage initial si valeur existante
    select.addEventListener('change', function() {
        updateSousType(this);
    });
});

// Ajouter un nouveau document
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

    categorieSelect.addEventListener('change', function() {
        updateSousType(this);
    });

    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.classList.add('btn', 'btn-outline-danger', 'btn-sm', 'mt-2');
    removeBtn.textContent = '{{ __("demandeClient.supprimer") }}';
    removeBtn.addEventListener('click', () => clone.remove());

    clone.appendChild(removeBtn);
    container.appendChild(clone);
});

// Ajouter un nouveau champ fichier
document.getElementById('add_file_btn').addEventListener('click', () => {
    const container = document.getElementById('fichiers_container');
    const original = container.querySelector('input[type="file"]');
    const clone = original.cloneNode(true);

    // Réinitialiser la valeur
    clone.value = "";

    // Créer bouton supprimer
    const wrapper = document.createElement('div');
    wrapper.classList.add('mb-3');
    wrapper.appendChild(clone);

    const cancelBtn = document.createElement('button');
    cancelBtn.type = 'button';
    cancelBtn.classList.add('btn', 'btn-outline-danger', 'btn-sm', 'mt-2');
    cancelBtn.innerText = '✖ Supprimer';
    cancelBtn.addEventListener('click', () => {
        wrapper.remove();
    });

    wrapper.appendChild(cancelBtn);
    container.appendChild(wrapper);
});

</script>
@endpush
