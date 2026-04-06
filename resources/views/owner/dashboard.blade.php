@extends('owner.layouts.app')

@section('title', 'Панель владельца')
@section('page_title', 'Панель владельца')

@section('header')
    <p class="text-muted mb-0 mt-1">Управляйте своими бизнесами, смотрите новые бронирования и следите за оплатами.</p>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">Мои бизнесы</p>
                    <h3 class="mb-0">{{ $stats['businesses'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">Все бронирования</p>
                    <h3 class="mb-0">{{ $stats['bookings'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">Ожидают</p>
                    <h3 class="mb-0">{{ $stats['pending_bookings'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">Оплачено</p>
                    <h3 class="mb-0">{{ number_format($stats['paid_total'], 0, '.', ' ') }} сум</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-xl-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Последние бронирования</h5>
                        <a href="{{ route('owner.businesses.index') }}" class="btn btn-sm btn-outline-primary">Мои бизнесы</a>
                    </div>

                    @if($recentBookings->isEmpty())
                        <p class="text-muted mb-0">Пока нет бронирований по вашим бизнесам.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                <tr>
                                    <th>Бизнес</th>
                                    <th>Клиент</th>
                                    <th>Услуга</th>
                                    <th>Дата</th>
                                    <th>Статус</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($recentBookings as $booking)
                                    <tr>
                                        <td>{{ $booking->business->name ?? '-' }}</td>
                                        <td>{{ $booking->user->name ?? '-' }}</td>
                                        <td>{{ $booking->service->name ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d.m.Y') }}</td>
                                        <td>{{ $booking->status }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Последние оплаты</h5>

                    @if($recentPayments->isEmpty())
                        <p class="text-muted mb-0">Оплат по вашим бизнесам пока нет.</p>
                    @else
                        <div class="d-flex flex-column gap-3">
                            @foreach($recentPayments as $payment)
                                <div class="border rounded-3 p-3">
                                    <div class="d-flex justify-content-between gap-3">
                                        <div>
                                            <div class="fw-semibold">{{ $payment->booking->business->name ?? '-' }}</div>
                                            <div class="text-muted small">{{ $payment->booking->service->name ?? '-' }}</div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-semibold">{{ number_format((float) $payment->amount, 0, '.', ' ') }} сум</div>
                                            <div class="text-muted small">{{ $payment->status }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
