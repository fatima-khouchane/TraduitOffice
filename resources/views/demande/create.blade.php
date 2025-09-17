@extends('layouts.app')

@section('title', __('demande.titre'))

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="col-md-10 d-flex justify-content-center">
      <div class="card shadow p-4 my-4" style="width: 100%; max-width: 900px;">
        <h2 class="mb-4 text-center">{{ __('demande.titre') }}</h2>

        @if (session('success'))
          <div class="alert alert-success">{{ __('demande.success_message') }}</div>
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

        <form action="{{ route('demande.store') }}" method="POST" enctype="multipart/form-data">
          @csrf

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">{{ __('demande.nom_demandeur') }}</label>
              <input type="text" name="nom_demandeur" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">{{ __('demande.nom_titulaire') }}</label>
              <input type="text" name="nom_titulaire" class="form-control" required>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">{{ __('demande.cin') }}</label>
              <input type="text" name="cin" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">{{ __('demande.telephone') }}</label>
              <input type="text" name="telephone" class="form-control" required>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">{{ __('demande.date_debut') }}</label>
              <input type="date" name="date_debut" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">{{ __('demande.date_fin') }}</label>
              <input type="date" name="date_fin" class="form-control" required>
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
            + {{ __('demande.ajouter_document') }}
          </button>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">{{ __('demande.prix_total') }}</label>
              <input type="number" step="0.01" name="prix_total" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">{{ __('demande.langue_origine') }}</label>
              <select name="langue_origine" class="form-control" required>
                <option value="">{{ __('demande.selectionner_langue') }}</option>
                <option value="Anglais">{{ __('demande.anglais') }}</option>
                <option value="Arabe">{{ __('demande.arabe') }}</option>
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">{{ __('demande.langue_souhaitee') }}</label>
              <select name="langue_souhaitee" class="form-control" required>
                <option value="">{{ __('demande.selectionner_langue') }}</option>
                <option value="Anglais">{{ __('demande.anglais') }}</option>
                <option value="Arabe">{{ __('demande.arabe') }}</option>
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">{{ __('demande.remarque') }}</label>
            <textarea name="remarque" rows="3" class="form-control"></textarea>
          </div>

          <div id="fichiers_container">
            <div class="mb-3">
              <label class="form-label">{{ __('demande.fichier_justificatif') }}</label>
              <input type="file" name="fichiers[]" class="form-control" accept="application/pdf,image/*">
            </div>
          </div>
<span>
          <button type="button" class="btn btn-secondary" id="add_file_btn">
            + {{ __('demande.ajouter_fichier') }}
          </button>

          <button type="submit" class="btn btn-primary ">
            {{ __('demande.envoyer_demande') }}
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  const translations = @json(__('documents.types'));
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
