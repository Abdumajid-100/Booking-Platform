@extends('admin.layouts.app')

@section('content')
    <div class="container py-4">

        <div class="card shadow-lg border-0 rounded-4 mx-auto" style="max-width:700px;">
            <div class="card-body p-4">

                <h4 class="fw-bold mb-4">Добавить бизнес</h4>

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

                <form method="POST" action="{{ route('admin.businesses.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <input name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Название">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" placeholder="Описание">{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <input name="address" value="{{ old('address') }}" class="form-control @error('address') is-invalid @enderror" placeholder="Адрес">
                        @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <input name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" placeholder="Телефон">
                        @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <h5 class="mt-4 mb-3 fw-bold">График работы</h5>

                    @php
                        $days = [
                            'Monday' => 'Понедельник',
                            'Tuesday' => 'Вторник',
                            'Wednesday' => 'Среда',
                            'Thursday' => 'Четверг',
                            'Friday' => 'Пятница',
                            'Saturday' => 'Суббота',
                            'Sunday' => 'Воскресенье',
                        ];
                    @endphp

                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-3">
                            @foreach($days as $key => $day)
                                <div class="row align-items-center py-2 border-bottom schedule-row">
                                    <div class="col-md-3 fw-semibold">
                                        {{ $day }}
                                    </div>

                                    <div class="col-md-3">
                                        <input
                                            type="time"
                                            name="schedule[{{ $key }}][start]"
                                            value="{{ old('schedule.' . $key . '.start') }}"
                                            class="form-control form-control-sm start-time"
                                        >
                                    </div>

                                    <div class="col-md-3">
                                        <input
                                            type="time"
                                            name="schedule[{{ $key }}][end]"
                                            value="{{ old('schedule.' . $key . '.end') }}"
                                            class="form-control form-control-sm end-time"
                                        >
                                    </div>

                                    <div class="col-md-3 text-end">
                                        <div class="form-check form-switch">
                                            <input
                                                class="form-check-input day-off"
                                                type="checkbox"
                                                name="schedule[{{ $key }}][day_off]"
                                                id="off_{{ $key }}"
                                                value="1"
                                                @checked(old('schedule.' . $key . '.day_off'))
                                            >
                                            <label class="form-check-label text-muted" for="off_{{ $key }}">
                                                Выходной
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <label class="form-label">Категория</label>
                        <select name="businesses_type_id" class="form-select @error('businesses_type_id') is-invalid @enderror">
                            <option value="">Выберите категорию</option>
                            @forelse($types as $type)
                                <option value="{{ $type->id }}" {{ old('businesses_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @empty
                                <option value="" disabled>Категории пока не добавлены</option>
                            @endforelse
                        </select>
                        @if($types->isEmpty())
                            <small class="text-muted">Заполните категории через сидер: <code>php artisan db:seed --class=BusinessesTypesSeeder</code></small>
                        @endif

                        @error('businesses_type_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button class="btn btn-primary w-100 mt-3">Сохранить</button>
                </form>

            </div>
        </div>

    </div>
@endsection
