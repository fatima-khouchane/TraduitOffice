<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" @if(app()->getLocale() === 'ar') dir="rtl" @endif>
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            height: 100vh;
            margin: 0;
        }

        .login-container {
            min-height: 100vh;
        }

        .login-card {
            background-color: white;
            border: none;
            border-radius: 20px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        .form-label {
            font-weight: 500;
        }

        .input-group-text {
            background-color: transparent;
            border-left: none;
            cursor: pointer;
        }

        .form-control:focus {
            box-shadow: none;
        }

        .input-group .form-control {
            border-right: none;
        }

        .input-group .input-group-text {
            border-left: none;
        }
    </style>
</head>
<body>
<div class="container d-flex align-items-center justify-content-center login-container">
    <div class="col-md-5 col-lg-4">
        <div class="login-card">
<h4 class="mb-4 text-center text-primary">{{ __('messages.login_page.title') }}</h4>

            @if(session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ url('/login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">{{ __('messages.login_page.email_label') }}</label>
<input type="email" name="email" class="form-control" required>
                    @error('email') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
<label class="form-label">{{ __('messages.login_page.password_label') }}</label>
                    <div class="input-group">
<input type="password" name="password" class="form-control" id="passwordInput" required>
                        <span class="input-group-text" id="togglePassword">
                            <i class="bi bi-eye-slash" id="iconToggle"></i>
                        </span>
                    </div>
                    @error('password') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    {{ __('messages.login_page.submit_button') }}
                </button>
 </form>
<div class="text-center mt-3">
    <a href="{{ route('register') }}" class="text-decoration-none">
        {{ __('messages.login_page.no_account') }}
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

<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('passwordInput');
    const iconToggle = document.getElementById('iconToggle');

    togglePassword.addEventListener('click', function () {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        iconToggle.classList.toggle('bi-eye');
        iconToggle.classList.toggle('bi-eye-slash');
    });
</script>
</body>
</html>
