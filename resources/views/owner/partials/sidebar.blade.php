<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar>
        <div id="sidebar-menu">
            <div class="logo-box">
                <a href="{{ route('owner.dashboard') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/admin/images/logo-sm.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/admin/images/logo-light.png') }}" alt="" height="24">
                    </span>
                </a>
                <a href="{{ route('owner.dashboard') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/admin/images/logo-sm.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/admin/images/logo-dark.png') }}" alt="" height="24">
                    </span>
                </a>
            </div>

            <ul id="side-menu">
                <li class="menu-title">Меню владельца</li>

                <li>
                    <a href="{{ route('owner.dashboard') }}" class="{{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                        <i data-feather="home"></i>
                        <span> Панель управления </span>
                    </a>
                </li>
                <li>
                    <a href="{{route('wallet')}}" class="{{ request()->routeIs('wallet') ? 'active' : '' }}">
                        <i data-feather="home"></i>
                        <span>Кошелёк  </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('owner.businesses.index') }}" class="{{ request()->routeIs('owner.businesses.index', 'owner.businesses.show', 'owner.businesses.edit') ? 'active' : '' }}">
                        <i data-feather="briefcase"></i>
                        <span> Мои бизнесы </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('owner.businesses.create') }}" class="{{ request()->routeIs('owner.businesses.create') ? 'active' : '' }}">
                        <i data-feather="plus-circle"></i>
                        <span> Создать бизнес </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('owner.dashboard') }}" class="{{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                        <i data-feather="credit-card"></i>
                        <span> История платежей </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <i data-feather="settings"></i>
                        <span> Настройки аккаунта </span>
                    </a>
                </li>

                <li class="menu-title mt-2">Переходы</li>

                <li>
                </li>

                <li>
                    <a href="{{ route('home') }}">
                        <i data-feather="globe"></i>
                        <span> Публичный сайт </span>
                    </a>
                </li>

                <li class="menu-title mt-2">Аккаунт</li>

                <li>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('owner-sidebar-logout-form').submit();">
                        <i data-feather="log-out"></i>
                        <span> Выйти </span>
                    </a>
                    <form id="owner-sidebar-logout-form" method="POST" action="{{ route('logout') }}" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
