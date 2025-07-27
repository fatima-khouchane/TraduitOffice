
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" @if(app()->getLocale() === 'ar') dir="rtl" @endif>
<head>
    <meta charset="UTF-8">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body>
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-lg border-0 p-5" style="max-width: 600px; width: 100%; background-color: #f8f9fa;">
        <div class="text-center">
            <div class="mb-4">
                <i class="bi bi-envelope-check-fill text-primary" style="font-size: 4rem;"></i>
            </div>

          <h2 class="mb-3 text-primary fw-bold">{{ __('auth.verify_title') }}</h2>

<p class="text-secondary fs-5">
    {{ __('auth.verify_message_line1') }}<br>
    {{ __('auth.verify_message_line2') }}
</p>

@if (session('message'))
    <div class="alert alert-success mt-4">
        {{ session('message') }}
    </div>
@endif

        </div>
    </div>
</div>
</body>
</html>
