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
                <li class="menu-title">Owner Menu</li>

                <li>
                    <a href="{{ route('owner.dashboard') }}" class="{{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                        <i data-feather="home"></i>
                        <span> Dashboard </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('owner.businesses.index') }}" class="{{ request()->routeIs('owner.businesses.*') ? 'active' : '' }}">
                        <i data-feather="briefcase"></i>
                        <span> My Businesses </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('owner.businesses.create') }}">
                        <i data-feather="plus-circle"></i>
                        <span> Add Business </span>
                    </a>
                </li>

                <li class="menu-title mt-2">Workspace</li>

                <li>
                    <a href="{{ route('dashboard') }}">
                        <i data-feather="user"></i>
                        <span> Client Cabinet </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('home') }}">
                        <i data-feather="globe"></i>
                        <span> Public Site </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
