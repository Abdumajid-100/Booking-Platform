<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8" />
    @php
        $clientTitle = match (true) {
            request()->routeIs('dashboard', 'client.dashboard') => 'Личный кабинет',
            request()->routeIs('profile.edit') => 'Настройки профиля',
            default => 'BroNix Client',
        };
        $pageTitle = trim($__env->yieldContent('title', $clientTitle));
    @endphp
    <title>{{ $pageTitle }} | BroNix</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Личный кабинет клиента BroNix." />
    <meta name="author" content="BroNix" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="{{ asset('assets/clients/images/favicon.ico') }}">
    <link href="{{ asset('assets/clients/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
    <link href="{{ asset('assets/clients/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('assets/clients/js/head.js') }}"></script>
</head>

<body data-menu-color="light" data-sidebar="default">
<div id="app-layout">
    <div class="topbar-custom">
        <div class="container-fluid">
            <div class="d-flex justify-content-between">
                <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
                    <li>
                        <button class="button-toggle-menu nav-link">
                            <i data-feather="menu" class="noti-icon"></i>
                        </button>
                    </li>
                    <li class="d-none d-lg-block">
                        <h5 class="mb-0">Личный кабинет, {{ auth()->user()->name ?? 'Пользователь' }}</h5>
                    </li>
                </ul>

                <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
                    <li class="d-none d-sm-flex">
                        <button type="button" class="btn nav-link" data-toggle="fullscreen">
                            <i data-feather="maximize" class="align-middle fullscreen noti-icon"></i>
                        </button>
                    </li>

                    <li class="dropdown notification-list topbar-dropdown">
                        <a class="nav-link dropdown-toggle nav-user me-0" data-bs-toggle="dropdown" href="#" role="button">
                            <span class="pro-user-name ms-1">{{ auth()->user()->name ?? 'Пользователь' }} <i class="mdi mdi-chevron-down"></i></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Аккаунт</h6>
                            </div>

                            <a href="{{ route('client.dashboard') }}" class="dropdown-item notify-item">
                                <i class="mdi mdi-view-dashboard-outline fs-16 align-middle"></i>
                                <span>Личный кабинет</span>
                            </a>

                            <a href="{{ route('profile.edit') }}" class="dropdown-item notify-item">
                                <i class="mdi mdi-account-cog-outline fs-16 align-middle"></i>
                                <span>Профиль</span>
                            </a>

                            <div class="dropdown-divider"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item notify-item">
                                    <i class="mdi mdi-location-exit fs-16 align-middle"></i>
                                    <span>Выйти</span>
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
