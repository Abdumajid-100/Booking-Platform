<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <title>Личный кабинет | BroNix</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Личный кабинет пользователя BroNix." />
    <meta name="author" content="BroNix" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="{{ asset('assets/admin/images/favicon.ico') }}">
    <link href="{{ asset('assets/admin/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
    <link href="{{ asset('assets/admin/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('assets/admin/js/head.js') }}"></script>
    <style>
        body{background:radial-gradient(circle at top left,rgba(59,130,246,.16),transparent 28%),radial-gradient(circle at top right,rgba(16,185,129,.12),transparent 24%),linear-gradient(180deg,#f4f7fb 0%,#eef3f9 100%)}
        .shell{padding:28px 0 36px}.panel,.side,.metric,.tile{border:1px solid rgba(148,163,184,.16);box-shadow:0 18px 40px rgba(15,23,42,.08)}
        .panel,.side,.tile{background:rgba(255,255,255,.92);backdrop-filter:blur(12px);border-radius:24px}
        .hero{border-radius:28px;overflow:hidden;color:#fff;background:radial-gradient(circle at 15% 20%,rgba(255,255,255,.18),transparent 22%),radial-gradient(circle at 85% 30%,rgba(255,255,255,.12),transparent 18%),linear-gradient(135deg,#0f172a 0%,#1d4ed8 45%,#0f766e 100%)}
        .hero-subtle{color:rgba(255,255,255,.74)}.glass{border:1px solid rgba(255,255,255,.16);border-radius:20px;background:rgba(255,255,255,.08)}
        .top-actions{display:flex;align-items:center;gap:10px}
        .top-chip{display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border-radius:999px;background:#fff;border:1px solid rgba(148,163,184,.18);box-shadow:0 8px 18px rgba(15,23,42,.05);color:#334155;font-size:13px;font-weight:600}
        .hero-mini{border-radius:18px;padding:16px 18px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.14)}
        .hero-mini-value{font-size:1.4rem;font-weight:700;line-height:1}
        .navx{display:flex;align-items:center;gap:12px;padding:12px 14px;border-radius:16px;color:#334155;text-decoration:none}.navx:hover,.navx.active{background:linear-gradient(135deg,rgba(37,99,235,.12),rgba(13,148,136,.12));color:#0f172a}
        .navx i{width:34px;height:34px;display:inline-flex;align-items:center;justify-content:center;border-radius:12px;background:#eff6ff;color:#2563eb}
        .side{position:sticky;top:96px}
        .metric{border-radius:22px;background:linear-gradient(180deg,#fff 0%,#f8fbff 100%);box-shadow:0 12px 30px rgba(15,23,42,.06);transition:transform .2s ease,box-shadow .2s ease}
        .metric:hover{transform:translateY(-3px);box-shadow:0 18px 34px rgba(15,23,42,.09)}
        .iconx{width:52px;height:52px;display:inline-flex;align-items:center;justify-content:center;border-radius:16px;font-size:1.2rem}
        .kicker{display:inline-flex;align-items:center;gap:8px;font-size:12px;letter-spacing:.08em;text-transform:uppercase;color:#64748b}
        .modern thead th{font-size:12px;text-transform:uppercase;letter-spacing:.05em;color:#64748b}.modern tbody tr:last-child td{border-bottom:0}
        .pill{display:inline-flex;align-items:center;padding:7px 12px;border-radius:999px;font-size:12px;font-weight:600}
        .time{position:relative;padding-left:28px}.time+.time{margin-top:18px}.time:before{content:"";position:absolute;left:6px;top:6px;width:10px;height:10px;border-radius:50%;background:#2563eb;box-shadow:0 0 0 6px rgba(37,99,235,.12)}
        .time:after{content:"";position:absolute;left:10px;top:20px;bottom:-18px;width:1px;background:rgba(148,163,184,.35)}.time:last-child:after{display:none}
        .biz{border-radius:20px;border:1px solid rgba(148,163,184,.16);background:linear-gradient(180deg,#fff 0%,#f8fafc 100%);transition:transform .2s ease,box-shadow .2s ease}.biz:hover{transform:translateY(-3px);box-shadow:0 16px 30px rgba(15,23,42,.08)}.acct{border-radius:18px;border:1px solid rgba(148,163,184,.16);background:#fff}
        @media (max-width:1199.98px){.side{position:relative;top:0}}
    </style>
</head>
<body data-menu-color="light" data-sidebar="default">
@php
    $paidPaymentsCount = $recentPayments->where('status', 'paid')->count();
    $successfulBookingsCount = $recentBookings->where('status', 'confirmed')->count();
    $latestBooking = $recentBookings->first();
@endphp
<div id="app-layout">
    <div class="topbar-custom">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div class="top-actions">
                    <h5 class="mb-0">BroNix Cabinet</h5>
                    <span class="top-chip"><i class="mdi mdi-lightning-bolt-outline"></i> Live workspace</span>
                </div>
                <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
                    <li class="dropdown notification-list topbar-dropdown">
                        <a class="nav-link dropdown-toggle nav-user me-0" data-bs-toggle="dropdown" href="#"><span class="pro-user-name ms-1">{{ $user->name }} <i class="mdi mdi-chevron-down"></i></span></a>
                        <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                            <div class="dropdown-header noti-title"><h6 class="text-overflow m-0">Добро пожаловать</h6></div>
                            <a href="{{ route('dashboard') }}" class="dropdown-item notify-item"><i class="mdi mdi-view-dashboard-outline fs-16 align-middle"></i><span>Кабинет</span></a>
                            <a href="{{ route('profile.edit') }}" class="dropdown-item notify-item"><i class="mdi mdi-account-cog-outline fs-16 align-middle"></i><span>Настройки аккаунта</span></a>
                            <a href="{{ route('home') }}" class="dropdown-item notify-item"><i class="mdi mdi-web fs-16 align-middle"></i><span>На главную</span></a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="dropdown-item notify-item"><i class="mdi mdi-location-exit fs-16 align-middle"></i><span>Выйти</span></button></form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="content-page ms-0">
        <div class="content">
            <div class="container-fluid shell">
                <div class="hero p-4 p-xl-5 mb-4">
                    <div class="row g-4 align-items-center">
                        <div class="col-xl-7">
                            <span class="badge bg-light text-dark px-3 py-2 rounded-pill mb-3">Личный кабинет клиента</span>
                            <h1 class="display-6 fw-bold mb-3">Более быстрый и наглядный контроль над записями и оплатами</h1>
                            <p class="hero-subtle fs-15 mb-4">BroNix собирает ваши бронирования, статус платежей, популярные компании и настройки профиля в одном технологичном интерфейсе.</p>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="#booking-history" class="btn btn-light text-dark fw-semibold">Смотреть бронирования</a>
                                <a href="#popular-businesses" class="btn btn-outline-light">Популярные бизнесы</a>
                                <a href="{{ route('profile.edit') }}" class="btn btn-outline-light">Настройки</a>
                            </div>
                            <div class="row g-3 mt-3">
                                <div class="col-md-6">
                                    <div class="hero-mini">
                                        <div class="small text-uppercase hero-subtle mb-2">Account status</div>
                                        <div class="hero-mini-value">Active</div>
                                        <div class="hero-subtle small mt-2">Workspace is ready for bookings, payments and profile updates</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="hero-mini">
                                        <div class="small text-uppercase hero-subtle mb-2">Needs attention</div>
                                        <div class="hero-mini-value">{{ $stats['pending_bookings'] }}</div>
                                        <div class="hero-subtle small mt-2">Pending bookings are waiting for your next action</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-5">
                            <div class="row g-3">
                                <div class="col-6"><div class="glass p-3 p-xl-4"><div class="small text-uppercase hero-subtle mb-2">Последняя запись</div><div class="fw-bold fs-5">{{ $latestBooking?->business?->name ?? 'Пока нет записей' }}</div><div class="hero-subtle small mt-2">{{ $latestBooking ? \Carbon\Carbon::parse($latestBooking->booking_date)->format('d.m.Y') : 'Создайте первую запись' }}</div></div></div>
                                <div class="col-6"><div class="glass p-3 p-xl-4"><div class="small text-uppercase hero-subtle mb-2">Подтверждено</div><div class="fw-bold fs-2">{{ $successfulBookingsCount }}</div><div class="hero-subtle small mt-2">успешных записей</div></div></div>
                                <div class="col-6"><div class="glass p-3 p-xl-4"><div class="small text-uppercase hero-subtle mb-2">Оплачено</div><div class="fw-bold fs-2">{{ $paidPaymentsCount }}</div><div class="hero-subtle small mt-2">платежей</div></div></div>
                                <div class="col-6"><div class="glass p-3 p-xl-4"><div class="small text-uppercase hero-subtle mb-2">Сумма</div><div class="fw-bold fs-4">{{ number_format($stats['paid_total'], 0, '.', ' ') }}</div><div class="hero-subtle small mt-2">сум через BroNix</div></div></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-12 col-xl-3">
                        <div class="side p-3 p-xl-4 mb-4">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center" style="width:56px;height:56px;"><span class="fw-bold">{{ strtoupper(\Illuminate\Support\Str::substr($user->name, 0, 1)) }}</span></div>
                                <div><div class="fw-semibold">{{ $user->name }}</div><div class="text-muted small">{{ $user->email }}</div></div>
                            </div>
                            <div class="d-flex flex-column gap-2">
                                <a class="navx active" href="#booking-history"><i class="mdi mdi-calendar-check-outline"></i><span>История бронирований</span></a>
                                <a class="navx" href="#payment-history"><i class="mdi mdi-credit-card-outline"></i><span>История оплаты</span></a>
                                <a class="navx" href="#notifications"><i class="mdi mdi-bell-outline"></i><span>Уведомления</span></a>
                                <a class="navx" href="#popular-businesses"><i class="mdi mdi-fire-circle"></i><span>Популярные бизнесы</span></a>
                                <a class="navx" href="#account-settings"><i class="mdi mdi-cog-outline"></i><span>Настройки аккаунта</span></a>
                            </div>
                        </div>
                        <div class="side p-3 p-xl-4">
                            <div class="kicker mb-3"><i class="mdi mdi-chart-donut"></i> Сводка</div>
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom"><span class="text-muted">Всего бронирований</span><span class="fw-semibold">{{ $stats['bookings'] }}</span></div>
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom"><span class="text-muted">Всего платежей</span><span class="fw-semibold">{{ $stats['payments'] }}</span></div>
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom"><span class="text-muted">Ожидают ответа</span><span class="fw-semibold">{{ $stats['pending_bookings'] }}</span></div>
                            <div class="d-flex justify-content-between align-items-center pt-2"><span class="text-muted">Оплачено</span><span class="fw-semibold">{{ number_format($stats['paid_total'], 0, '.', ' ') }} сум</span></div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-9">
                        <div class="row g-4 mb-4">
                            <div class="col-md-6 col-xl-3"><div class="metric p-4 h-100"><div class="d-flex justify-content-between"><div><div class="text-muted mb-2">Бронирований</div><h3 class="mb-1">{{ $stats['bookings'] }}</h3><div class="small text-muted">Все записи</div></div><div class="iconx bg-primary-subtle text-primary"><i class="mdi mdi-calendar-multiselect"></i></div></div></div></div>
                            <div class="col-md-6 col-xl-3"><div class="metric p-4 h-100"><div class="d-flex justify-content-between"><div><div class="text-muted mb-2">Платежей</div><h3 class="mb-1">{{ $stats['payments'] }}</h3><div class="small text-muted">История оплат</div></div><div class="iconx bg-success-subtle text-success"><i class="mdi mdi-cash-fast"></i></div></div></div></div>
                            <div class="col-md-6 col-xl-3"><div class="metric p-4 h-100"><div class="d-flex justify-content-between"><div><div class="text-muted mb-2">В ожидании</div><h3 class="mb-1">{{ $stats['pending_bookings'] }}</h3><div class="small text-muted">Нуждаются в подтверждении</div></div><div class="iconx bg-warning-subtle text-warning"><i class="mdi mdi-timer-sand"></i></div></div></div></div>
                            <div class="col-md-6 col-xl-3"><div class="metric p-4 h-100"><div class="d-flex justify-content-between"><div><div class="text-muted mb-2">Оплачено</div><h3 class="mb-1 fs-4">{{ number_format($stats['paid_total'], 0, '.', ' ') }}</h3><div class="small text-muted">сум через BroNix</div></div><div class="iconx bg-info-subtle text-info"><i class="mdi mdi-chart-line"></i></div></div></div></div>
                        </div>
                        <div id="booking-history" class="panel p-4 mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3"><div><div class="kicker mb-2"><i class="mdi mdi-calendar-check-outline"></i> Основной раздел</div><h4 class="mb-0">История бронирований</h4></div><span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">{{ $recentBookings->count() }} записей</span></div>
                            @if($recentBookings->isEmpty())
                                <div class="text-muted">У вас пока нет бронирований.</div>
                            @else
                                <div class="table-responsive"><table class="table modern align-middle mb-0"><thead><tr><th>Бизнес</th><th>Услуга</th><th>Дата</th><th>Время</th><th>Статус</th></tr></thead><tbody>
                                @foreach($recentBookings as $booking)
                                    @php
                                        $bookingStatusClass = match ($booking->status) {'confirmed' => 'bg-success-subtle text-success','pending' => 'bg-warning-subtle text-warning',default => 'bg-danger-subtle text-danger',};
                                        $bookingStatusLabel = match ($booking->status) {'confirmed' => 'Подтверждено','pending' => 'В ожидании',default => 'Отменено',};
                                    @endphp
                                    <tr><td><div class="fw-semibold">{{ $booking->business->name ?? 'Без названия' }}</div><div class="text-muted small">{{ $booking->business->type->name ?? 'Без категории' }}</div></td><td>{{ $booking->service->name ?? 'Услуга не указана' }}</td><td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d.m.Y') }}</td><td>{{ \Illuminate\Support\Str::substr($booking->start_time, 0, 5) }} - {{ \Illuminate\Support\Str::substr($booking->end_time, 0, 5) }}</td><td><span class="pill {{ $bookingStatusClass }}">{{ $bookingStatusLabel }}</span></td></tr>
                                @endforeach
                                </tbody></table></div>
                            @endif
                        </div>
                        <div id="payment-history" class="panel p-4 mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3"><div><div class="kicker mb-2"><i class="mdi mdi-credit-card-outline"></i> Финансы</div><h4 class="mb-0">История оплаты</h4></div><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">{{ $recentPayments->count() }} платежей</span></div>
                            @if($recentPayments->isEmpty())
                                <div class="text-muted">История оплат пока пуста.</div>
                            @else
                                <div class="table-responsive"><table class="table modern align-middle mb-0"><thead><tr><th>Компания</th><th>Услуга</th><th>Сумма</th><th>Метод</th><th>Статус</th></tr></thead><tbody>
                                @foreach($recentPayments as $payment)
                                    @php
                                        $paymentStatusClass = match ($payment->status) {'paid' => 'bg-success-subtle text-success','pending' => 'bg-warning-subtle text-warning',default => 'bg-danger-subtle text-danger',};
                                        $paymentStatusLabel = match ($payment->status) {'paid' => 'Оплачено','pending' => 'Ожидает оплаты',default => 'Ошибка',};
                                    @endphp
                                    <tr><td>{{ $payment->booking->business->name ?? 'Компания не найдена' }}</td><td>{{ $payment->booking->service->name ?? 'Услуга не указана' }}</td><td>{{ number_format((float) $payment->amount, 0, '.', ' ') }} сум</td><td>{{ ucfirst($payment->payment_method) }}</td><td><span class="pill {{ $paymentStatusClass }}">{{ $paymentStatusLabel }}</span></td></tr>
                                @endforeach
                                </tbody></table></div>
                            @endif
                        </div>
                        <div class="row g-4 mb-4">
                            <div class="col-lg-5">
                                <div id="notifications" class="panel p-4 h-100">
                                    <div class="kicker mb-2"><i class="mdi mdi-bell-outline"></i> События</div><h4 class="mb-4">Уведомления</h4>
                                    <div class="time"><div class="fw-semibold">Неподтвержденные записи</div><div class="text-muted small mt-1">У вас {{ $stats['pending_bookings'] }} бронирований со статусом ожидания.</div></div>
                                    <div class="time"><div class="fw-semibold">Оплаты и активность</div><div class="text-muted small mt-1">Всего успешных оплат: {{ $paidPaymentsCount }}. Следите за новыми статусами платежей в этой панели.</div></div>
                                    <div class="time"><div class="fw-semibold">Настройки аккаунта</div><div class="text-muted small mt-1">Проверьте актуальность имени, email и параметров безопасности профиля.</div></div>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div id="popular-businesses" class="panel p-4 h-100">
                                    <div class="d-flex justify-content-between align-items-center mb-3"><div><div class="kicker mb-2"><i class="mdi mdi-fire-circle"></i> Тренды</div><h4 class="mb-0">Популярные бизнесы</h4></div><a href="{{ route('business.page') }}" class="btn btn-sm btn-outline-primary">Смотреть все</a></div>
                                    <div class="row g-3">
                                        @forelse($popularBusinesses as $business)
                                            <div class="col-md-6"><div class="biz p-3 h-100"><div class="d-flex justify-content-between gap-3"><div><h6 class="mb-1">{{ $business->name }}</h6><div class="text-muted small">{{ $business->type->name ?? 'Без категории' }}</div></div><span class="badge bg-primary-subtle text-primary align-self-start">{{ $business->bookings_count }} заказов</span></div><p class="text-muted small mt-3 mb-0">{{ \Illuminate\Support\Str::limit($business->description ?: 'Популярный бизнес на платформе BroNix.', 100) }}</p></div></div>
                                        @empty
                                            <div class="col-12"><div class="text-muted">Популярные компании появятся здесь после накопления бронирований.</div></div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="account-settings" class="panel p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3"><div><div class="kicker mb-2"><i class="mdi mdi-cog-outline"></i> Профиль</div><h4 class="mb-0">Настройки аккаунта</h4></div><a href="{{ route('profile.edit') }}" class="btn btn-primary">Открыть настройки</a></div>
                            <div class="row g-3">
                                <div class="col-md-6 col-xl-3"><div class="acct p-3 h-100"><div class="text-muted small mb-1">Имя</div><div class="fw-semibold">{{ $user->name }}</div></div></div>
                                <div class="col-md-6 col-xl-3"><div class="acct p-3 h-100"><div class="text-muted small mb-1">Email</div><div class="fw-semibold">{{ $user->email }}</div></div></div>
                                <div class="col-md-6 col-xl-3"><div class="acct p-3 h-100"><div class="text-muted small mb-1">Безопасность</div><div class="fw-semibold">Управление паролем</div></div></div>
                                <div class="col-md-6 col-xl-3"><div class="acct p-3 h-100"><div class="text-muted small mb-1">Профиль</div><div class="fw-semibold">Редактирование данных</div></div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer"><div class="container-fluid"><div class="row"><div class="col fs-13 text-muted text-center">&copy; <script>document.write(new Date().getFullYear())</script> BroNix</div></div></div></footer>
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
