@extends('admin.layouts.app')

@section('content')
    <div class="container py-4">
        <div class="card shadow-lg border-0 rounded-4 mx-auto" style="max-width:700px;">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-4">Редактировать тип бизнеса</h4>

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Ошибка!</strong> Проверьте правильность данных:
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.businesses-types.update', $businessesType) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Название</label>
                        <input
                            id="name"
                            name="name"
                            value="{{ old('name', $businessesType->name) }}"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Например: Салон красоты"
                        >
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-warning w-100 text-white">Обновить</button>
                </form>
            </div>
        </div>
    </div>
@endsection
