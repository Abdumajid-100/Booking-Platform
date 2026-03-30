@extends('admin.layouts.app')

@section('content')
    <div class="container py-4">
        <div class="card border-0 shadow-sm rounded-4 mx-auto" style="max-width:920px;">
            <div class="card-header bg-white border-0 pt-4 px-4 px-lg-5">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div>
                        <h4 class="fw-bold mb-1">Редактировать бизнес</h4>
                        <p class="text-muted mb-0">Обновите информацию и график работы</p>
                    </div>
                    <a href="{{ route('admin.businesses.index') }}" class="btn btn-outline-secondary btn-sm">Назад к списку</a>
                </div>
            </div>

            <div class="card-body px-4 px-lg-5 pb-4">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Ошибка!</strong> Проверьте правильность данных:
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.businesses.update', $business) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Название</label>
                            <input
                                name="name"
                                value="{{ old('name', $business->name) }}"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Введите название"
                            >
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Телефон</label>
                            <input
                                name="phone"
                                value="{{ old('phone', $business->phone) }}"
                                class="form-control @error('phone') is-invalid @enderror"
                                placeholder="Введите телефон"
                            >
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Описание</label>
                            <textarea
                                name="description"
                                rows="3"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Краткое описание бизнеса"
                            >{{ old('description', $business->description) }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Адрес</label>
                            <input
                                name="address"
                                value="{{ old('address', $business->address) }}"
                                class="form-control @error('address') is-invalid @enderror"
                                placeholder="Введите адрес"
                            >
                            @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Категория</label>
                            <select name="businesses_type_id" class="form-select @error('businesses_type_id') is-invalid @enderror">
                                <option value="">Выберите категорию</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" {{ old('businesses_type_id', $business->businesses_type_id) == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('businesses_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

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
                        $schedulesByDay = $business->schedules->keyBy('day_of_week');
                    @endphp

                    <div class="border rounded-4 p-3 p-md-4 mb-4 bg-light-subtle">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="fw-bold mb-0">График работы</h5>
                            <small class="text-muted">Укажите часы или отметьте выходной</small>
                        </div>

                        <div class="d-none d-md-block px-2 pb-2 border-bottom text-muted small fw-semibold">
                            <div class="row">
                                <div class="col-md-4">День</div>
                                <div class="col-md-3">Начало</div>
                                <div class="col-md-3">Конец</div>
                                <div class="col-md-2 text-end">Статус</div>
                            </div>
                        </div>

                        @foreach($days as $key => $day)
                            @php $row = $schedulesByDay->get($key); @endphp
                            <div class="row align-items-center py-2 px-2 border-bottom schedule-row">
                                <div class="col-12 col-md-4 fw-semibold mb-2 mb-md-0">{{ $day }}</div>

                                <div class="col-6 col-md-3">
                                    <input
                                        type="time"
                                        name="schedule[{{ $key }}][start]"
                                        value="{{ old('schedule.' . $key . '.start', $row?->start_time ? \Illuminate\Support\Str::substr($row->start_time, 0, 5) : null) }}"
                                        class="form-control form-control-sm start-time"
                                    >
                                </div>

                                <div class="col-6 col-md-3">
                                    <input
                                        type="time"
                                        name="schedule[{{ $key }}][end]"
                                        value="{{ old('schedule.' . $key . '.end', $row?->end_time ? \Illuminate\Support\Str::substr($row->end_time, 0, 5) : null) }}"
                                        class="form-control form-control-sm end-time"
                                    >
                                </div>

                                <div class="col-12 col-md-2 text-md-end mt-2 mt-md-0">
                                    <div class="form-check form-switch d-inline-flex align-items-center gap-2 mb-0">
                                        <input
                                            class="form-check-input day-off"
                                            type="checkbox"
                                            name="schedule[{{ $key }}][day_off]"
                                            id="off_{{ $key }}"
                                            value="1"
                                            @checked(old('schedule.' . $key . '.day_off', $row?->is_day_off))
                                        >
                                        <label class="form-check-label text-muted" for="off_{{ $key }}">Выходной</label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="row g-3 align-items-end mb-2">
                        <div class="col-md-8">
                            <label class="form-label">Фото</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                            @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            @if($business->image)
                                <img src="{{ asset('storage/' . $business->image) }}" class="rounded shadow-sm w-100" style="height:90px; object-fit:cover;" alt="{{ $business->name }}">
                            @endif
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <button class="btn btn-warning text-white px-4">Сохранить изменения</button>
                        <a href="{{ route('admin.businesses.show', $business) }}" class="btn btn-outline-primary">Открыть карточку</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
