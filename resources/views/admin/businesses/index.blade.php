@extends('admin.layouts.app')

@section('content')
    <div class="container py-4">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1">Бизнесы</h2>
                <small class="text-muted">Каталог ваших бизнесов</small>
            </div>
            <a href="{{ route('admin.businesses.create') }}" class="btn btn-primary shadow-sm">+ Добавить</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success shadow-sm mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($businesses->isEmpty())
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center py-5">
                    <h5 class="mb-2">Пока нет бизнесов</h5>
                    <p class="text-muted mb-3">Создайте первый бизнес, чтобы он появился в каталоге.</p>
                    <a href="{{ route('admin.businesses.create') }}" class="btn btn-primary">Добавить бизнес</a>
                </div>
            </div>
        @else
            <div class="row g-4">
                @foreach($businesses as $business)
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="ratio ratio-16x9 bg-light">
                                @if($business->image)
                                    <img
                                        src="{{ asset('storage/' . $business->image) }}"
                                        alt="{{ $business->name }}"
                                        style="width:100%; height:100%; object-fit:cover;"
                                    >
                                @else
                                    <div class="d-flex align-items-center justify-content-center text-muted fw-semibold">
                                        Нет фото
                                    </div>
                                @endif
                            </div>

                            <div class="card-body d-flex flex-column">
                                @php
                                    $daysMap = [
                                        'Monday' => 'ПН',
                                        'Tuesday' => 'ВТ',
                                        'Wednesday' => 'СР',
                                        'Thursday' => 'ЧТ',
                                        'Friday' => 'ПТ',
                                        'Saturday' => 'СБ',
                                        'Sunday' => 'ВС',
                                    ];
                                    $daysOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                    $schedulesByDay = $business->schedules->keyBy(function ($item) {
                                        return strtolower((string) $item->day_of_week);
                                    });
                                    $workingDays = collect($daysOrder)->map(function ($dayKey) use ($schedulesByDay, $daysMap) {
                                        $row = $schedulesByDay->get(strtolower($dayKey));

                                        if (!$row || $row->is_day_off) {
                                            return null;
                                        }

                                        $start = $row->start_time ? \Illuminate\Support\Str::substr($row->start_time, 0, 5) : null;
                                        $end = $row->end_time ? \Illuminate\Support\Str::substr($row->end_time, 0, 5) : null;
                                        $hasRealTime = $start && $end && !($start === '00:00' && $end === '00:00');

                                        if (!$hasRealTime) {
                                            return null;
                                        }

                                        return [
                                            'day' => $daysMap[$dayKey],
                                            'time' => $start . '-' . $end,
                                        ];
                                    })->filter()->values();
                                    $timeRanges = $workingDays->pluck('time')->unique()->values();
                                    $workTimeLabel = $timeRanges->isEmpty()
                                        ? null
                                        : ($timeRanges->count() === 1 ? $timeRanges->first() : $timeRanges->implode(', '));
                                    $workingDayNames = $workingDays->pluck('day')->implode(', ');
                                    $dayOffCount = $business->schedules->where('is_day_off', true)->count();
                                    $dayOffLabel = ($dayOffCount % 10 == 1 && $dayOffCount % 100 != 11) ? 'день'
                                        : (($dayOffCount % 10 >= 2 && $dayOffCount % 10 <= 4 && ($dayOffCount % 100 < 10 || $dayOffCount % 100 >= 20)) ? 'дня' : 'дней');
                                @endphp

                                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                    <h5 class="card-title mb-0">{{ $business->name }}</h5>
                                    <span class="badge bg-primary-subtle text-primary">
                                        {{ $business->type->name ?? 'Без категории' }}
                                    </span>
                                </div>

                                <p class="card-text text-muted mb-3">
                                    {{ \Illuminate\Support\Str::limit($business->description ?? 'Описание не заполнено', 100) }}
                                </p>

                                <div class="small text-muted mb-3">
                                    <div class="mb-1"><strong>Адрес:</strong> {{ $business->address ?? '-' }}</div>
                                    <div class="mb-1"><strong>Телефон:</strong> {{ $business->phone ?? '-' }}</div>
                                    <div class="mb-1"><strong>Время работы:</strong> {{ $workTimeLabel ?? 'Не указано' }}</div>
                                    <div class="mb-1"><strong>Выходные:</strong> {{ $dayOffCount }} {{ $dayOffLabel }}</div>
                                    <div class="mb-1"><strong>Дни работы:</strong> {{ $workingDayNames !== '' ? $workingDayNames : 'Не указан' }}</div>
                                    @if($workingDays->isNotEmpty())
                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                            @foreach($workingDays as $item)
                                                <span class="small rounded-pill bg-light border px-2 py-1 text-dark">
                                                    {{ $item['day'] }} {{ $item['time'] }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                    <div><strong>Создано:</strong> {{ $business->created_at?->format('d.m.Y') ?? '-' }}</div>
                                </div>

                                <div class="mt-auto d-flex gap-2">
                                    <a href="{{ route('admin.businesses.show', $business) }}" class="btn btn-sm btn-outline-primary">Открыть</a>
                                    <a href="{{ route('admin.businesses.edit', $business) }}" class="btn btn-sm btn-outline-warning">Изменить</a>
                                    <form action="{{ route('admin.businesses.destroy', $business) }}" method="POST" class="ms-auto">
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

    </div>
@endsection
