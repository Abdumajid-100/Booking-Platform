<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">@yield('page_title', 'Owner Panel')</h4>
                    @yield('header')
                </div>
            </div>

            @yield('content')
        </div>
    </div>

    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col fs-13 text-muted text-center">
                    &copy; <script>document.write(new Date().getFullYear())</script> BroNix Owner Panel
                </div>
            </div>
        </div>
    </footer>
</div>
