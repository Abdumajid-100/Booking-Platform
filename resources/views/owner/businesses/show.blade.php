@extends('owner.layouts.app')

@section('title', 'Карточка бизнеса')
@section('page_title', 'Карточка бизнеса')

@section('content')
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mx-auto" style="max-width:980px;">
        <div class="row g-0">
            <div class="col-12 col-lg-5">
                @if($business->image)
                    <img src="{{ asset('storage/' . $business->image) }}" alt="{{ $business->name }}" class="w-100 h-100" style="min-height:340px; max-height:520px; object-fit:cover;">
                @else
                    <div class="d-flex align-items-center justify-content-center bg-light text-muted h-100" style="min-height:340px;">Нет фото</div>
                @endif
            </div>

            <div class="col-12 col-lg-7">
                <div class="card-body p-4 p-lg-5 h-100 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                        <div>
                            <h2 class="fw-bold mb-1">{{ $business->name }}</h2>
                            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">{{ $business->type->name ?? 'Без категории' }}</span>
                        </div>
                        <span class="text-muted small">ID: {{ $business->id }}</span>
                    </div>

                    <p class="text-muted mb-4">{{ $business->description ?: 'Описание не заполнено.' }}</p>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-6"><div class="p-3 bg-light rounded-3 h-100"><div class="small text-muted mb-1">Адрес</div><div class="fw-semibold">{{ $business->address ?: '-' }}</div></div></div>
                        <div class="col-sm-6"><div class="p-3 bg-light rounded-3 h-100"><div class="small text-muted mb-1">Телефон</div><div class="fw-semibold">{{ $business->phone ?: '-' }}</div></div></div>
                    </div>

                    <div class="mb-4">
                        <div class="fw-semibold mb-2">Услуги</div>
                        @if($business->services->isEmpty())
                            <div class="small text-muted">Услуги пока не добавлены.</div>
                        @else
                            <div class="row g-2">
                                @foreach($business->services as $service)
                                    <div class="col-sm-6">
                                        <div class="p-3 bg-light rounded-3 h-100">
                                            <div class="fw-semibold">{{ $service->name }}</div>
                                            <div class="small text-muted mt-1">Цена: {{ number_format((float) $service->price, 0, '.', ' ') }} сум</div>
                                            <div class="small text-muted">Длительность: {{ $service->duration }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="mt-auto d-flex flex-wrap gap-2">
                        <a href="{{ route('owner.businesses.index') }}" class="btn btn-dark">Назад</a>
                        <a href="{{ route('owner.businesses.edit', $business) }}" class="btn btn-outline-warning">Изменить</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
