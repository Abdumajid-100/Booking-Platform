@extends('owner.layouts.app')

@section('title', 'Мои бизнесы')
@section('page_title', 'Мои бизнесы')

@section('header')
    <p class="text-muted mb-0 mt-1">Все бизнесы, которыми вы управляете в BroNix.</p>
@endsection

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div></div>
        <a href="{{ route('owner.businesses.create') }}" class="btn btn-primary shadow-sm">+ Добавить бизнес</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm mb-4">{{ session('success') }}</div>
    @endif

    @if($businesses->isEmpty())
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body text-center py-5">
                <h5 class="mb-2">У вас пока нет бизнесов</h5>
                <p class="text-muted mb-3">Создайте первый бизнес для запуска owner-панели.</p>
                <a href="{{ route('owner.businesses.create') }}" class="btn btn-primary">Добавить бизнес</a>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($businesses as $business)
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="ratio ratio-16x9 bg-light">
                            @if($business->image)
                                <img src="{{ asset('storage/' . $business->image) }}" alt="{{ $business->name }}" style="width:100%; height:100%; object-fit:cover;">
                            @else
                                <div class="d-flex align-items-center justify-content-center text-muted fw-semibold">Нет фото</div>
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                <h5 class="card-title mb-0">{{ $business->name }}</h5>
                                <span class="badge bg-primary-subtle text-primary">{{ $business->type->name ?? 'Без категории' }}</span>
                            </div>
                            <p class="text-muted mb-2">{{ \Illuminate\Support\Str::limit($business->description ?? 'Описание не заполнено', 90) }}</p>
                            <div class="small text-muted mb-3">
                                <div class="mb-1"><strong>Адрес:</strong> {{ $business->address ?? '-' }}</div>
                                <div class="mb-1"><strong>Телефон:</strong> {{ $business->phone ?? '-' }}</div>
                                <div><strong>Услуги:</strong> {{ $business->services->isNotEmpty() ? $business->services->pluck('name')->implode(', ') : 'Не добавлены' }}</div>
                            </div>
                            <div class="mt-auto d-flex gap-2">
                                <a href="{{ route('owner.businesses.show', $business) }}" class="btn btn-sm btn-outline-primary">Открыть</a>
                                <a href="{{ route('owner.businesses.edit', $business) }}" class="btn btn-sm btn-outline-warning">Изменить</a>
                                <form action="{{ route('owner.businesses.destroy', $business) }}" method="POST" class="ms-auto">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Удалить бизнес?')">Удалить</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
