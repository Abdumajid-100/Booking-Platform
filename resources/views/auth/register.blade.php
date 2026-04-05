<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация | BroNix</title>
    <meta name="description" content="Регистрация нового пользователя на платформе бронирования BroNix.">

    <link href="{{ asset('assets/public/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets/public/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/public/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/public/css/main.css') }}" rel="stylesheet">

    <style>
        body.auth-page {
            min-height: 100vh;
            background:
                radial-gradient(circle at top right, rgba(13, 110, 253, 0.16), transparent 28%),
                radial-gradient(circle at bottom left, rgba(255, 193, 7, 0.14), transparent 30%),
                linear-gradient(135deg, #fdfefe 0%, #eff6ff 50%, #f6fbf7 100%);
            color: #10233a;
        }

        .auth-shell {
            min-height: 100vh;
            padding: 32px 0;
        }

        .auth-card {
            border: 0;
            border-radius: 32px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 24px 80px rgba(16, 35, 58, 0.12);
            backdrop-filter: blur(10px);
        }

        .auth-form {
            padding: 48px;
        }

        .auth-aside {
            padding: 48px;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.08), rgba(255, 255, 255, 0.02)),
                linear-gradient(150deg, #10233a 0%, #0d4d8b 55%, #1c7c54 100%);
            color: #fff;
        }

        .brand-mark {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            font-size: 1.3rem;
            font-weight: 700;
            color: inherit;
            text-decoration: none;
        }

        .brand-icon {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .stats-card {
            margin-top: 28px;
            border-radius: 24px;
            padding: 24px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            margin-top: 18px;
        }

        .stats-item {
            border-radius: 18px;
            padding: 18px;
            background: rgba(255, 255, 255, 0.08);
        }

        .stats-item strong {
            display: block;
            font-size: 1.4rem;
            margin-bottom: 6px;
        }

        .auth-form h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .auth-form .lead {
            color: #5f7288;
            margin-bottom: 28px;
        }

        .form-control {
            min-height: 52px;
            border-radius: 16px;
            border-color: #d7e3f3;
            padding: 0.85rem 1rem;
            box-shadow: none;
        }

        .form-control:focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.12);
        }

        .btn-auth {
            min-height: 54px;
            border-radius: 16px;
            font-weight: 600;
            font-size: 1rem;
        }

        .link-plain {
            text-decoration: none;
        }

        .text-soft {
            color: rgba(255, 255, 255, 0.82);
        }

        @media (max-width: 991.98px) {
            .auth-aside,
            .auth-form {
                padding: 32px 24px;
            }
        }
    </style>
</head>
<body class="auth-page">
<section class="auth-shell d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-11">
                <div class="auth-card">
                    <div class="row g-0">
                        <div class="col-lg-6 order-lg-2">
                            <div class="auth-aside h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <a href="{{ route('home') }}" class="brand-mark">
                                        <span class="brand-icon"><i class="bi bi-stars"></i></span>
                                        <span>BroNix</span>
                                    </a>

                                    <h2 class="mt-4 mb-3 fw-bold" style="font-size: clamp(2rem, 3vw, 3rem); line-height: 1.05;">
                                        Создайте аккаунт и откройте личный кабинет
                                    </h2>
                                    <p class="text-soft mb-0">
                                        После регистрации вы сможете управлять своими данными, переходить к бронированию и работать с сервисом из единого кабинета.
                                    </p>
                                </div>

                                <div class="stats-card">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="bi bi-graph-up-arrow"></i>
                                        <span>Что вы получите</span>
                                    </div>

                                    <div class="stats-grid">
                                        <div class="stats-item">
                                            <strong>1</strong>
                                            <span class="text-soft">Личный кабинет сразу после регистрации</span>
                                        </div>
                                        <div class="stats-item">
                                            <strong>24/7</strong>
                                            <span class="text-soft">Доступ к бронированию в любое время</span>
                                        </div>
                                        <div class="stats-item">
                                            <strong>Удобно</strong>
                                            <span class="text-soft">Простая форма без лишних шагов</span>
                                        </div>
                                        <div class="stats-item">
                                            <strong>Безопасно</strong>
                                            <span class="text-soft">Защищённый вход и управление данными</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 order-lg-1">
                            <div class="auth-form h-100 d-flex flex-column justify-content-center">
                                <div class="mb-4">
                                    <a href="{{ route('home') }}" class="link-plain text-success fw-semibold">
                                        <i class="bi bi-arrow-left me-1"></i> На главную
                                    </a>
                                </div>

                                <h1>Регистрация</h1>
                                <p class="lead">Заполните данные, чтобы создать профиль и сразу попасть в личный кабинет.</p>

                                @if ($errors->any())
                                    <div class="alert alert-danger rounded-4" role="alert">
                                        {{ $errors->first() }}
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('register') }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Имя</label>
                                        <input class="form-control" id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Введите ваше имя">
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="you@example.com">
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Пароль</label>
                                        <input class="form-control" id="password" type="password" name="password" required autocomplete="new-password" placeholder="Придумайте пароль">
                                    </div>

                                    <div class="mb-4">
                                        <label for="password_confirmation" class="form-label">Подтверждение пароля</label>
                                        <input class="form-control" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Повторите пароль">
                                    </div>

                                    <div class="d-grid mb-4">
                                        <button class="btn btn-success btn-auth" type="submit">Создать аккаунт</button>
                                    </div>
                                </form>

                                <p class="mb-0 text-muted">
                                    Уже зарегистрированы?
                                    <a class="link-plain text-success fw-semibold" href="{{ route('login') }}">Войти</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="{{ asset('assets/public/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
