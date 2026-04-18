<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    @php
        $ownerTitle = match (true) {
            request()->routeIs('owner.dashboard') => 'РџР°РЅРµР»СЊ РІР»Р°РґРµР»СЊС†Р°',
            request()->routeIs('owner.businesses.index') => 'РњРѕРё Р±РёР·РЅРµСЃС‹',
            request()->routeIs('owner.businesses.create') => 'Р”РѕР±Р°РІРёС‚СЊ Р±РёР·РЅРµСЃ',
            request()->routeIs('owner.businesses.edit') => 'Р РµРґР°РєС‚РёСЂРѕРІР°С‚СЊ Р±РёР·РЅРµСЃ',
            request()->routeIs('owner.businesses.show') => 'РљР°СЂС‚РѕС‡РєР° Р±РёР·РЅРµСЃР°',
            default => 'Owner Panel',
        };
        $pageTitle = trim($__env->yieldContent('title', $ownerTitle));
    @endphp
    <title>{{ $pageTitle }} | BroNix</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Owner panel for managing businesses in BroNix." />
    <meta name="author" content="BroNix" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="{{ asset('assets/admin/images/favicon.ico') }}">
    <link href="{{ asset('assets/admin/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
    <link href="{{ asset('assets/admin/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('assets/admin/js/head.js') }}"></script>
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
                        <h5 class="mb-0">Owner Panel, {{ auth()->user()->name ?? 'User' }}</h5>
                    </li>
                </ul>

                <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
                    <li class="dropdown notification-list topbar-dropdown">
                        <a class="nav-link dropdown-toggle nav-user me-0" data-bs-toggle="dropdown" href="#" role="button">
                            <span class="pro-user-name ms-1">{{ auth()->user()->name ?? 'User' }} <i class="mdi mdi-chevron-down"></i></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Настройка профила</h6>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="dropdown-item notify-item">
                                <i class="mdi mdi-account-cog-outline fs-16 align-middle"></i>
                                <span>Настройка аккаунта</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item notify-item">
                                    <i class="mdi mdi-location-exit fs-16 align-middle"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
