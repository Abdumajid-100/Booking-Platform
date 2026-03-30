@extends('admin.layouts.app')

@section('content')
    <div class="container py-4">
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden mx-auto" style="max-width:700px;">
            <div class="card-body p-4">
                <h3 class="fw-bold mb-3">{{ $businessesType->name }}</h3>

                <div class="small text-muted mb-2"><strong>ID:</strong> {{ $businessesType->id }}</div>
                <div class="small text-muted mb-2"><strong>Бизнесов с этим типом:</strong> {{ $businessesType->businesses_count }}</div>
                <div class="small text-muted"><strong>Создано:</strong> {{ $businessesType->created_at?->format('d.m.Y H:i') ?? '-' }}</div>

                <div class="mt-4 d-flex gap-2">
                    <a href="{{ route('admin.businesses-types.edit', $businessesType) }}" class="btn btn-warning text-white">Изменить</a>
                    <a href="{{ route('admin.businesses-types.index') }}" class="btn btn-dark">Назад</a>
                </div>
            </div>
        </div>
    </div>
@endsection
