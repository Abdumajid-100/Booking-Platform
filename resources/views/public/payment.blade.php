@include('public.partials.header')

<main class="main">
    <section class="section" style="padding-top: 140px;">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center">
                    <h1 class="mb-3">Оплата бронирования</h1>
                    <p class="mb-0">Проверьте детали записи и перейдите к оплате. После подключения платежной системы здесь можно будет завершить платеж онлайн.</p>
                </div>
            </div>

            <div class="row g-4 justify-content-center">
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 p-lg-5">
                            <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                                <div>
                                    <span class="badge bg-warning-subtle text-warning mb-2">Ожидает оплаты</span>
                                    <h2 class="h3 mb-1">{{ $booking->business->name }}</h2>
                                    <p class="text-muted mb-0">{{ $booking->business->type->name ?? 'Без категории' }}</p>
                                </div>
                                <div class="text-end">
                                    <div class="small text-muted">Сумма</div>
                                    <div class="h4 mb-0">{{ number_format((float) $booking->payment->amount, 0, '.', ' ') }} сум</div>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="border rounded-4 p-3 h-100">
                                        <div class="small text-muted mb-1">Услуга</div>
                                        <strong>{{ $booking->service->name }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-4 p-3 h-100">
                                        <div class="small text-muted mb-1">Дата</div>
                                        <strong>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d.m.Y') }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-4 p-3 h-100">
                                        <div class="small text-muted mb-1">Время</div>
                                        <strong>{{ \Illuminate\Support\Str::substr($booking->start_time, 0, 5) }} - {{ \Illuminate\Support\Str::substr($booking->end_time, 0, 5) }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-4 p-3 h-100">
                                        <div class="small text-muted mb-1">Статус</div>
                                        <strong>{{ $booking->payment->status === 'pending' ? 'Ожидает оплаты' : $booking->payment->status }}</strong>
                                    </div>
                                </div>
                            </div>

                            @if($booking->comment)
                                <div class="border rounded-4 p-3 mb-4">
                                    <div class="small text-muted mb-1">Комментарий</div>
                                    <div>{{ $booking->comment }}</div>
                                </div>
                            @endif

                            <div class="d-flex flex-column gap-3">
                                <button type="button" class="btn btn-primary btn-lg">Перейти к онлайн-оплате</button>
                                <a href="{{ route('booking.page') }}" class="btn btn-outline-secondary">Вернуться к бронированию</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <h3 class="h5 mb-3">Что дальше</h3>
                            <div class="small text-muted d-flex flex-column gap-3">
                                <div>1. Вы заполнили данные бронирования.</div>
                                <div>2. Система создала запись и платеж со статусом `pending`.</div>
                                <div>3. Следующим шагом можно подключить реальную оплату через Stripe, Click, Payme или другой провайдер.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

@include('public.partials.footer')
