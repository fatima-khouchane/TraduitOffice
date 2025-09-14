<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" @if(app()->getLocale() === 'ar') dir="rtl" @endif>
<head>
    <meta charset="UTF-8">
    <title>Inscription Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            height: 100vh;
        }

        .register-container {
            min-height: 100vh;
        }

        .register-card {
            background-color: white;
            border: none;
            border-radius: 20px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }
    </style>
</head>
<body>
<div class="container d-flex align-items-center justify-content-center register-container">
    <div class="col-md-5 col-lg-5">
        <div class="register-card">
<h4 class="mb-4 text-center text-primary">{{ __('messages.register_page.title') }}</h4>

            <form method="POST" action="{{ route('client.register') }}">
    @csrf

    <div class="mb-3">
        <label>{{ __('messages.register_page.name_label') }}</label>
        <input type="text" name="name" class="form-control" required>
        @error('name') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label>{{ __('messages.register_page.email_label') }}</label>
        <input type="email" name="email" class="form-control" required>
        @error('email') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label>{{ __('messages.register_page.phone_label') }}</label>
        <input type="text" name="phone" class="form-control" required>
        @error('phone') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label>{{ __('messages.register_page.password_label') }}</label>
        <input type="password" name="password" class="form-control" required>
        @error('password') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label>{{ __('messages.register_page.confirm_password_label') }}</label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary w-100">{{ __('messages.register_page.submit_button') }}</button>
</form>
<div class="text-center mt-3">
    <a href="{{ route('login') }}">
        {{ __('messages.register_page.already_account') }}
    </a>
</div>
<div class="text-center mt-2">
    <!-- Lien retour à l'accueil avec style différent -->
    <a href="{{ url('/') }}" class="text-decoration-none text-muted small d-block">
        <i class="bi bi-arrow-left"></i> {{ __('messages.login_page.welcome_back') }}
    </a>
</div>

        </div>
    </div>
</div>
</body>
</html>
