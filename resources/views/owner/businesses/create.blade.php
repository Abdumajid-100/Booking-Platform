@extends('owner.layouts.app')

@section('title', 'Добавить бизнес')
@section('page_title', 'Добавить бизнес')

@section('content')
    <div class="card shadow-lg border-0 rounded-4 mx-auto" style="max-width:800px;">
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

            <form method="POST" action="{{ route('owner.businesses.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Название</label>
                        <input name="name" value="{{ old('name') }}" class="form-control" placeholder="Название">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Телефон</label>
                        <input name="phone" value="{{ old('phone') }}" class="form-control" placeholder="Телефон">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Описание</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Описание">{{ old('description') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Адрес</label>
                        <input name="address" value="{{ old('address') }}" class="form-control" placeholder="Адрес">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Категория</label>
                        <select name="businesses_type_id" class="form-select">
                            <option value="">Выберите категорию</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}" @selected(old('businesses_type_id') == $type->id)>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @php
                    $serviceRows = old('services', array_fill(0, 3, ['name' => '', 'price' => '', 'duration' => '']));
                    $days = ['Monday' => 'Понедельник', 'Tuesday' => 'Вторник', 'Wednesday' => 'Среда', 'Thursday' => 'Четверг', 'Friday' => 'Пятница', 'Saturday' => 'Суббота', 'Sunday' => 'Воскресенье'];
                @endphp

                <div class="border rounded-4 p-3 p-md-4 mb-4 bg-light-subtle mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Услуги бизнеса</h5>
                        <small class="text-muted">Добавьте хотя бы одну услугу</small>
                    </div>
                    @foreach($serviceRows as $index => $service)
                        <div class="row g-2 align-items-end mb-3">
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
                        <div class="row align-items-center py-2 border-bottom schedule-row">
                            <div class="col-md-3 fw-semibold">{{ $day }}</div>
                            <div class="col-md-3"><input type="time" name="schedule[{{ $key }}][start]" value="{{ old('schedule.' . $key . '.start') }}" class="form-control form-control-sm start-time"></div>
                            <div class="col-md-3"><input type="time" name="schedule[{{ $key }}][end]" value="{{ old('schedule.' . $key . '.end') }}" class="form-control form-control-sm end-time"></div>
                            <div class="col-md-3 text-end">
                                <div class="form-check form-switch">
                                    <input class="form-check-input day-off" type="checkbox" name="schedule[{{ $key }}][day_off]" id="off_{{ $key }}" value="1" @checked(old('schedule.' . $key . '.day_off'))>
                                    <label class="form-check-label text-muted" for="off_{{ $key }}">Выходной</label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mb-3">
                    <label class="form-label">Фото</label>
                    <input type="file" name="image" class="form-control">
                </div>

                <button class="btn btn-primary w-100 mt-3">Сохранить</button>
            </form>
        </div>
    </div>
@endsection
