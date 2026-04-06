<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход | BroNix</title>
    <meta name="description" content="Вход в личный кабинет платформы бронирования BroNix.">

    <link href="{{ asset('assets/public/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets/public/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/public/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/public/css/main.css') }}" rel="stylesheet">

    <style>
        body.auth-page {
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(13, 110, 253, 0.18), transparent 28%),
                radial-gradient(circle at bottom right, rgba(25, 135, 84, 0.14), transparent 30%),
                linear-gradient(135deg, #f4f7fb 0%, #eef5ff 45%, #f8fbff 100%);
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
            background: rgba(255, 255, 255, 0.94);
            box-shadow: 0 24px 80px rgba(16, 35, 58, 0.12);
            backdrop-filter: blur(10px);
        }

        .auth-aside {
            position: relative;
            padding: 48px;
            background: linear-gradient(160deg, #0d6efd 0%, #0b57cc 52%, #0a2f6f 100%);
            color: #fff;
        }

        .auth-aside::before,
        .auth-aside::after {
            content: "";
            position: absolute;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.10);
        }

        .auth-aside::before {
            width: 220px;
            height: 220px;
            top: -70px;
            right: -70px;
        }

        .auth-aside::after {
            width: 180px;
            height: 180px;
            bottom: -60px;
            left: -40px;
        }

        .auth-form {
            padding: 48px;
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
            background: rgba(255, 255, 255, 0.16);
            border: 1px solid rgba(255, 255, 255, 0.22);
        }

        .auth-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.18);
            font-size: 0.95rem;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 28px 0 0;
        }

        .feature-list li {
            display: flex;
            gap: 14px;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .feature-list i {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.14);
        }

        .auth-panel-title {
            font-size: clamp(2rem, 3vw, 3rem);
            line-height: 1.05;
            margin: 20px 0 16px;
            font-weight: 700;
        }

        .auth-panel-text {
            max-width: 520px;
            color: rgba(255, 255, 255, 0.85);
            font-size: 1.02rem;
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
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.12);
        }

        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .btn-auth {
            min-height: 54px;
            border-radius: 16px;
            font-weight: 600;
            font-size: 1rem;
        }

        .form-hint,
        .form-hint a {
            color: #5f7288;
        }

        .link-plain {
            text-decoration: none;
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
                        <div class="col-lg-6">
                            <div class="auth-aside h-100">
                                <a href="{{ route('home') }}" class="brand-mark">
                                    <span class="brand-icon"><i class="bi bi-calendar2-check"></i></span>
                                    <span>BroNix</span>
                                </a>

                                <div class="auth-badge mt-4">
                                    <i class="bi bi-lightning-charge-fill"></i>
                                    <span>Онлайн-бронирование без лишних шагов</span>
                                </div>

                                <h2 class="auth-panel-title">Вход в систему управления записями</h2>
                                <p class="auth-panel-text">
                                    Управляйте бронированиями, выбирайте услуги, следите за историей записей и работайте с бизнес-профилем в одном кабинете.
                                </p>

                                <ul class="feature-list">
                                    <li>
                                        <i class="bi bi-person-lines-fill"></i>
                                        <div>
                                            <strong>Личный кабинет</strong>
                                            <div class="auth-panel-text">Редактирование профиля, пароля и персональных данных.</div>
                                        </div>
                                    </li>
                                    <li>
                                        <i class="bi bi-building"></i>
                                        <div>
                                            <strong>Бизнес и услуги</strong>
                                            <div class="auth-panel-text">Быстрый доступ к компаниям, услугам и времени работы.</div>
                                        </div>
                                    </li>
                                    <li>
                                        <i class="bi bi-shield-check"></i>
                                        <div>
                                            <strong>Безопасный доступ</strong>
                                            <div class="auth-panel-text">Авторизация только по вашим данным и защищённым маршрутам.</div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="auth-form h-100 d-flex flex-column justify-content-center">
                                <div class="mb-4">
                                    <a href="{{ route('home') }}" class="link-plain text-primary fw-semibold">
                                        <i class="bi bi-arrow-left me-1"></i> На главную
                                    </a>
                                </div>

                                <h1>С возвращением</h1>
                                <p class="lead">Войдите, чтобы перейти в личный кабинет и продолжить работу с бронированиями.</p>

                                @if (session('status'))
                                    <div class="alert alert-success rounded-4" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger rounded-4" role="alert">
                                        {{ $errors->first() }}
                                    </div>
                                @endif

                                <form action="{{ route('login') }}" method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input class="form-control" type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="username" autofocus placeholder="you@example.com">
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Пароль</label>
                                        <input class="form-control" name="password" type="password" required id="password" autocomplete="current-password" placeholder="Введите пароль">
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="remember">Запомнить меня</label>
                                        </div>

                                        @if (Route::has('password.request'))
                                            <a class="link-plain text-primary fw-semibold" href="{{ route('password.request') }}">Забыли пароль?</a>
                                        @endif
                                    </div>

                                    <div class="d-grid mb-4">
                                        <button class="btn btn-primary btn-auth" type="submit">Войти в кабинет</button>
                                    </div>
                                </form>

                                <p class="form-hint mb-0">
                                    Ещё нет аккаунта?
                                    <a class="link-plain text-primary fw-semibold" href="{{ route('register') }}">Создать аккаунт</a>
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
