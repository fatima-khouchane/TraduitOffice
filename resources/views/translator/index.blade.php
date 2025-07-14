@extends('layouts.app')

@section('title', 'Liste des traducteurs')

@section('content')
<div class="container mt-5">
  <div class="card shadow rounded-4">
    <div class="card-header bg-primary text-white rounded-top-4">
      <h4 class="mb-0 text-center">Liste des traducteurs</h4>
    </div>

    <div class="card-body bg-light">
      @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-primary">
            <tr>
              <th>#</th>
              <th>Nom</th>
              <th>Email</th>
              <th>Téléphone</th>
              <th>Date d'ajout</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($translators as $index => $translator)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-person-circle fs-5 text-primary"></i>
                    <strong>{{ $translator->name }}</strong>
                  </div>
                </td>
                <td>{{ $translator->email }}</td>
                <td>{{ $translator->phone ?? '-' }}</td>
                <td>{{ $translator->created_at->format('d/m/Y H:i') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-muted">Aucun traducteur trouvé</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
