<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    @php
        $publicTitle = match (true) {
            request()->routeIs('home') => 'Главная',
            request()->routeIs('business.page') => 'Каталог бизнесов',
            request()->routeIs('booking.page') => 'Бронирование',
            request()->routeIs('booking.payment') => 'Оплата бронирования',
            default => 'BroNix',
        };
    @endphp
    <title>{{ $publicTitle }} | BroNix</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <link href="{{ asset('assets/public/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets/public/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <link href="{{ asset('assets/public/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/public/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/public/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/public/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/public/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/public/css/main.css') }}" rel="stylesheet">
</head>

<body class="index-page">

<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">
        <a href="{{ route('home') }}" class="logo d-flex align-items-center me-auto">
            <h1 class="sitename">BroNix</h1>
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Главная страница<br></a></li>
                <li><a href="{{ route('business.page') }}" class="{{ request()->routeIs('business.page') ? 'active' : '' }}">Страница бизнеса</a></li>
                <li><a href="{{ route('booking.page') }}" class="{{ request()->routeIs('booking.page', 'booking.payment') ? 'active' : '' }}">Страница бронирования</a></li>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        @auth
            <a class="btn-getstarted flex-md-shrink-0" href="{{ route('dashboard') }}">Личный кабинет</a>
        @else
            <a class="btn-getstarted flex-md-shrink-0" href="{{ route('login') }}">Войти</a>
        @endauth
    </div>
</header>
