<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <title>Register | Booking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Create an account in the Booking Platform." />
    <meta name="author" content="Booking Platform" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="{{ asset('assets/admin/images/favicon.ico') }}">
    <link href="{{ asset('assets/admin/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
    <link href="{{ asset('assets/admin/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('assets/admin/js/head.js') }}"></script>
</head>

<body>
<div class="account-page">
    <div class="container-fluid p-0">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-6">
                <div class="col-md-8 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-0 p-0 p-lg-3">
                                <div class="mb-0 border-0 p-md-4 p-lg-0">
                                    <div class="mb-4 p-0 text-lg-start text-center">
                                        <div class="auth-brand">
                                            <a href="/" class="logo logo-light">
                                                <span class="logo-lg">
                                                    <img src="{{ asset('assets/admin/images/logo-light-3.png') }}" alt="Booking logo" height="24">
                                                </span>
                                            </a>
                                            <a href="/" class="logo logo-dark">
                                                <span class="logo-lg">
                                                    <img src="{{ asset('assets/admin/images/logo-dark-3.png') }}" alt="Booking logo" height="24">
                                                </span>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="auth-title-section mb-4 text-lg-start text-center">
                                        <h3 class="text-dark fw-semibold mb-3">Create your account</h3>
                                        <p class="text-muted fs-14 mb-0">Register to start managing bookings, schedules, and customer activity.</p>
                                    </div>

                                    <div class="pt-0">
                                        @if ($errors->any())
                                            <div class="alert alert-danger" role="alert">
                                                {{ $errors->first() }}
                                            </div>
                                        @endif

                                        <form method="POST" action="{{ route('register') }}">
                                            @csrf

                                            <div class="form-group mb-3">
                                                <label for="name" class="form-label">Full name</label>
                                                <input class="form-control" id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Enter your full name">
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="email" class="form-label">Email address</label>
                                                <input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="Enter your email">
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input class="form-control" id="password" type="password" name="password" required autocomplete="new-password" placeholder="Create a password">
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="password_confirmation" class="form-label">Confirm password</label>
                                                <input class="form-control" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repeat your password">
                                            </div>

                                            <div class="form-group mb-0 row mt-4">
                                                <div class="col-12">
                                                    <div class="d-grid">
                                                        <button class="btn btn-primary fw-semibold" type="submit">Register</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="text-center text-muted mt-3">
                                            <p class="mb-0">Already registered? <a class="text-primary ms-2 fw-medium" href="{{ route('login') }}">Log in</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/admin/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/waypoints/lib/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/jquery.counterup/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/app.js') }}"></script>
</body>
</html>
