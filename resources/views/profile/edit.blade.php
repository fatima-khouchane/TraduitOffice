@extends('layouts.client')

@section('title', __('profile.edit_title'))

@section('content')
    <div class="card shadow-lg p-4" style="border-radius: 15px; max-width: 600px; margin: 0 auto;">
        <h3 class="text-center mb-4">{{ __('profile.edit_title') }}</h3>

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
                <label class="form-label">{{ __('profile.name') }}</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('profile.password') }}</label>
                <div class="input-group">
                    <input type="password" name="password" class="form-control" id="password">
                    <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                        <i class="bi bi-eye-slash" id="iconPassword"></i>
                    </span>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('profile.confirm_password') }}</label>
                <div class="input-group">
                    <input type="password" name="password_confirmation" class="form-control" id="passwordConfirm">
                    <span class="input-group-text" id="toggleConfirm" style="cursor: pointer;">
                        <i class="bi bi-eye-slash" id="iconConfirm"></i>
                    </span>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">{{ __('profile.save') }}</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
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
