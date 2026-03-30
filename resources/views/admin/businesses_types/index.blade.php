@extends('admin.layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1">Типы бизнеса</h2>
                <small class="text-muted">Справочник категорий для бизнеса</small>
            </div>
            <a href="{{ route('admin.businesses-types.create') }}" class="btn btn-primary shadow-sm">+ Добавить</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success shadow-sm mb-3">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger shadow-sm mb-3">
                {{ session('error') }}
            </div>
        @endif

        @if($types->isEmpty())
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center py-5">
                    <h5 class="mb-2">Типов пока нет</h5>
                    <p class="text-muted mb-3">Добавьте первый тип бизнеса.</p>
                    <a href="{{ route('admin.businesses-types.create') }}" class="btn btn-primary">Добавить тип</a>
                </div>
            </div>
        @else
            <div class="card border-0 shadow-sm rounded-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th style="width: 80px;">#</th>
                            <th>Название</th>
                            <th style="width: 170px;">Создано</th>
                            <th class="text-end" style="width: 260px;">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($types as $type)
                            <tr>
                                <td>{{ $type->id }}</td>
                                <td>{{ $type->name }}</td>
                                <td>{{ $type->created_at?->format('d.m.Y H:i') ?? '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.businesses-types.show', $type) }}" class="btn btn-sm btn-outline-primary">Открыть</a>
                                    <a href="{{ route('admin.businesses-types.edit', $type) }}" class="btn btn-sm btn-outline-warning">Изменить</a>
                                    <form action="{{ route('admin.businesses-types.destroy', $type) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Удалить этот тип бизнеса?')"
                                        >
                                            Удалить
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection
