@extends('layouts.client')

@section('title', 'Modifier le profil')

@section('content')
    <div class="card shadow-lg p-4" style="border-radius: 15px; max-width: 600px; margin: 0 auto;">
        <h3 class="text-center mb-4">Modifier le profil</h3>

        {{-- Message de succès --}}
        @if(session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif

        {{-- Message d'erreurs --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profil.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nom</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mot de passe (laisser vide si inchangé)</label>
                <div class="input-group">
                    <input type="password" name="password" class="form-control" id="password">
                    <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                        <i class="bi bi-eye-slash" id="iconPassword"></i>
                    </span>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirmer le mot de passe</label>
                <div class="input-group">
                    <input type="password" name="password_confirmation" class="form-control" id="passwordConfirm">
                    <span class="input-group-text" id="toggleConfirm" style="cursor: pointer;">
                        <i class="bi bi-eye-slash" id="iconConfirm"></i>
                    </span>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    // Afficher/masquer mot de passe
    document.getElementById('togglePassword')?.addEventListener('click', function () {
        const input = document.getElementById('password');
        const icon = document.getElementById('iconPassword');
        input.type = input.type === 'password' ? 'text' : 'password';
        icon.classList.toggle('bi-eye');
        icon.classList.toggle('bi-eye-slash');
    });

    document.getElementById('toggleConfirm')?.addEventListener('click', function () {
        const input = document.getElementById('passwordConfirm');
        const icon = document.getElementById('iconConfirm');
        input.type = input.type === 'password' ? 'text' : 'password';
        icon.classList.toggle('bi-eye');
        icon.classList.toggle('bi-eye-slash');
    });
</script>
@endpush
