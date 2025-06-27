@extends('layouts.app')

@section('content')


<div class="center-form-wrapper">
    <div class="card shadow-lg p-4 w-100" style="width:5000px;border-radius: 15px;">
        <h3 class="text-center mb-4">Modifier le profil</h3>

        @if(session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
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
</div>

<!-- Icônes Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<!-- Script pour afficher/masquer les mots de passe -->
<script>
    // Mot de passe
    document.getElementById('togglePassword').addEventListener('click', function () {
        const input = document.getElementById('password');
        const icon = document.getElementById('iconPassword');
        const type = input.type === 'password' ? 'text' : 'password';
        input.type = type;
        icon.classList.toggle('bi-eye');
        icon.classList.toggle('bi-eye-slash');
    });

    // Confirmation
    document.getElementById('toggleConfirm').addEventListener('click', function () {
        const input = document.getElementById('passwordConfirm');
        const icon = document.getElementById('iconConfirm');
        const type = input.type === 'password' ? 'text' : 'password';
        input.type = type;
        icon.classList.toggle('bi-eye');
        icon.classList.toggle('bi-eye-slash');
    });
</script>

@endsection
