@extends('owner.layouts.app')

@section('title', 'Редактировать бизнес')
@section('page_title', 'Редактировать бизнес')

@section('content')
    <div class="card border-0 shadow-sm rounded-4 mx-auto" style="max-width:920px;">
        <div class="card-body p-4">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Ошибка!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('owner.businesses.update', $business) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Название</label>
                        <input name="name" value="{{ old('name', $business->name) }}" class="form-control" placeholder="Название">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Телефон</label>
                        <input name="phone" value="{{ old('phone', $business->phone) }}" class="form-control" placeholder="Телефон">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Описание</label>
                        <textarea name="description" rows="3" class="form-control" placeholder="Описание">{{ old('description', $business->description) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Адрес</label>
                        <input name="address" value="{{ old('address', $business->address) }}" class="form-control" placeholder="Адрес">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Категория</label>
                        <select name="businesses_type_id" class="form-select">
                            <option value="">Выберите категорию</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}" @selected(old('businesses_type_id', $business->businesses_type_id) == $type->id)>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @php
                    $serviceRows = old('services', $business->services->map(fn ($service) => ['id' => $service->id, 'name' => $service->name, 'price' => $service->price, 'duration' => $service->duration])->toArray());
                    if (count($serviceRows) < 3) {
                        $serviceRows = array_pad($serviceRows, 3, ['id' => null, 'name' => '', 'price' => '', 'duration' => '']);
                    }
                    $days = ['Monday' => 'Понедельник', 'Tuesday' => 'Вторник', 'Wednesday' => 'Среда', 'Thursday' => 'Четверг', 'Friday' => 'Пятница', 'Saturday' => 'Суббота', 'Sunday' => 'Воскресенье'];
                    $schedulesByDay = $business->schedules->keyBy('day_of_week');
                @endphp

                <div class="border rounded-4 p-3 p-md-4 mb-4 bg-light-subtle">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Услуги бизнеса</h5>
                        <small class="text-muted">Обновите услуги</small>
                    </div>
                    @foreach($serviceRows as $index => $service)
                        <div class="row g-2 align-items-end mb-3">
                            <input type="hidden" name="services[{{ $index }}][id]" value="{{ $service['id'] ?? '' }}">
                            <div class="col-md-5"><input type="text" name="services[{{ $index }}][name]" value="{{ $service['name'] ?? '' }}" class="form-control" placeholder="Название услуги"></div>
                            <div class="col-md-3"><input type="number" step="0.01" min="0" name="services[{{ $index }}][price]" value="{{ $service['price'] ?? '' }}" class="form-control" placeholder="Цена"></div>
                            <div class="col-md-4"><input type="text" name="services[{{ $index }}][duration]" value="{{ $service['duration'] ?? '' }}" class="form-control" placeholder="Длительность"></div>
                        </div>
                    @endforeach
                </div>

                <div class="border rounded-4 p-3 p-md-4 mb-4 bg-light-subtle">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">График работы</h5>
                        <small class="text-muted">Укажите часы или отметьте выходной</small>
                    </div>
                    @foreach($days as $key => $day)
                        @php $row = $schedulesByDay->get($key); @endphp
                        <div class="row align-items-center py-2 border-bottom schedule-row">
                            <div class="col-md-3 fw-semibold">{{ $day }}</div>
                            <div class="col-md-3"><input type="time" name="schedule[{{ $key }}][start]" value="{{ old('schedule.' . $key . '.start', $row?->start_time ? \Illuminate\Support\Str::substr($row->start_time, 0, 5) : null) }}" class="form-control form-control-sm start-time"></div>
                            <div class="col-md-3"><input type="time" name="schedule[{{ $key }}][end]" value="{{ old('schedule.' . $key . '.end', $row?->end_time ? \Illuminate\Support\Str::substr($row->end_time, 0, 5) : null) }}" class="form-control form-control-sm end-time"></div>
                            <div class="col-md-3 text-end">
                                <div class="form-check form-switch">
                                    <input class="form-check-input day-off" type="checkbox" name="schedule[{{ $key }}][day_off]" id="off_{{ $key }}" value="1" @checked(old('schedule.' . $key . '.day_off', $row?->is_day_off))>
                                    <label class="form-check-label text-muted" for="off_{{ $key }}">Выходной</label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="row g-3 align-items-end mb-2">
                    <div class="col-md-8">
                        <label class="form-label">Фото</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                    <div class="col-md-4">
                        @if($business->image)
                            <img src="{{ asset('storage/' . $business->image) }}" class="rounded shadow-sm w-100" style="height:90px; object-fit:cover;" alt="{{ $business->name }}">
                        @endif
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-4">
                    <button class="btn btn-warning text-white px-4">Сохранить изменения</button>
                    <a href="{{ route('owner.businesses.show', $business) }}" class="btn btn-outline-primary">Открыть карточку</a>
                </div>
            </form>
        </div>
    </div>
@endsection
