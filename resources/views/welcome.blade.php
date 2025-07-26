<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" @if(app()->getLocale() === 'ar') dir="rtl" @endif>
<head>
    <meta charset="UTF-8">
    <title>Bienvenue sur TraduOffice</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
        }

        .welcome-container {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            max-width: 1100px;
            width: 100%;
            display: flex;
            flex-wrap: wrap;
        }

        .text-section {
            padding: 60px 40px;
            flex: 1 1 500px;
            animation: fadeInLeft 1.2s ease-in-out;
        }

        .text-section h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #0d6efd;
        }

        .text-section p {
            font-size: 1.1rem;
            margin-top: 20px;
            color: #555;
            line-height: 1.6;
        }

        .btn-custom {
            border-radius: 30px;
            padding: 10px 25px;
            font-size: 1rem;
            font-weight: 500;
            opacity: 0;
            animation: fadeInUp 1s ease forwards;
        }

        .btn-primary {
            animation-delay: 1.4s;
        }

        .btn-outline-primary {
            animation-delay: 1.6s;
        }

        .image-section {
            flex: 1 1 400px;
            background: #eaf0fa;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
            animation: fadeInRight 1.5s ease-in-out;
        }

        .image-section img {
            max-width: 100%;
            height: auto;
            animation: floatImage 3s ease-in-out infinite;
        }

        /* Animations */
        @keyframes floatImage {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        @keyframes fadeInLeft {
            0% {
                opacity: 0;
                transform: translateX(-40px);
            }
            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            0% {
                opacity: 0;
                transform: translateX(40px);
            }
            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
            from {
                opacity: 0;
                transform: translateY(20px);
            }
        }

        @media (max-width: 768px) {
            .welcome-container {
                flex-direction: column;
            }

            .text-section {
                padding: 40px 30px;
            }
        }
    </style>
</head>
<body>

<div class="welcome-container">

    <div class="text-section">
        <div>
          <a href="{{ route('lang.switch', 'fr') }}">🇫🇷 Français</a> |
    <a href="{{ route('lang.switch', 'en') }}">🇬🇧 English</a> |
    <a href="{{ route('lang.switch', 'ar') }}">🇸🇦 العربية</a>
        </div>
<h1>{{ __('messages.welcome_title') }}</h1>
       <p>{!! __('messages.welcome_description') !!}</p>


        <div class="d-flex gap-3 mt-4 flex-wrap">
            <a href="{{ route('login') }}" class="btn btn-primary btn-custom">{{ __('messages.login') }}</a>
    <a href="{{ route('register') }}" class="btn btn-outline-primary btn-custom">{{ __('messages.register') }}</a>
        </div>
    </div>

    <div class="image-section">
        <img src="{{ asset('welcome.png') }}" alt="Image de traduction">
    </div>
</div>

</body>
</html>
