@include('public.partials.header')

<main class="main">
    <section class="section" style="padding-top: 140px;">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-8">
                    <h1 class="mb-3">Страница бизнеса</h1>
                    <p class="mb-0">Здесь отображаются бизнесы, которые уже добавлены в систему. Клиент может выбрать подходящую компанию и перейти к бронированию.</p>
                </div>
            </div>

            @if($businesses->isEmpty())
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body text-center py-5">
                                <h3 class="h4 mb-3">Пока нет доступных бизнесов</h3>
                                <p class="text-muted mb-4">Когда в базе появятся компании, они будут отображаться на этой странице.</p>
                                <a href="{{ route('home') }}" class="btn btn-primary">Вернуться на главную</a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="row g-4">
                    @foreach($businesses as $business)
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
                            $schedulesByDay = $business->schedules->keyBy(fn ($item) => strtolower((string) $item->day_of_week));
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
                                    'time' => $start . ' - ' . $end,
                                ];
                            })->filter()->values();
                            $workTimeLabel = $workingDays->pluck('time')->unique()->implode(', ');
                        @endphp

                        <div class="col-12 col-md-6 col-xl-4">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                                <div class="ratio ratio-16x9 bg-light">
                                    @if($business->image)
                                        <img
                                            src="{{ asset('storage/' . $business->image) }}"
                                            alt="{{ $business->name }}"
                                            style="width: 100%; height: 100%; object-fit: cover;"
                                        >
                                    @else
                                        <div class="d-flex align-items-center justify-content-center text-muted fw-semibold">
                                            Нет фото
                                        </div>
                                    @endif
                                </div>

                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                                        <h3 class="h5 mb-0">{{ $business->name }}</h3>
                                        <span class="badge bg-primary-subtle text-primary">
                                            {{ $business->type->name ?? 'Без категории' }}
                                        </span>
                                    </div>

                                    <p class="text-muted mb-3">
                                        {{ \Illuminate\Support\Str::limit($business->description ?: 'Описание бизнеса пока не заполнено.', 120) }}
                                    </p>

                                    <div class="small text-muted mb-4">
                                        <div class="mb-2"><strong>Адрес:</strong> {{ $business->address ?: 'Не указан' }}</div>
                                        <div class="mb-2"><strong>Телефон:</strong> {{ $business->phone ?: 'Не указан' }}</div>
                                        <div class="mb-2"><strong>Время работы:</strong> {{ $workTimeLabel !== '' ? $workTimeLabel : 'Не указано' }}</div>
                                        <div><strong>Дни работы:</strong> {{ $workingDays->isNotEmpty() ? $workingDays->pluck('day')->implode(', ') : 'Не указаны' }}</div>
                                    </div>

                                    @if($workingDays->isNotEmpty())
                                        <div class="d-flex flex-wrap gap-2 mb-4">
                                            @foreach($workingDays as $item)
                                                <span class="small rounded-pill bg-light border px-2 py-1 text-dark">
                                                    {{ $item['day'] }} {{ $item['time'] }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="mt-auto">
                                        <a href="{{ route('booking.page') }}" class="btn btn-primary w-100">Перейти к бронированию</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</main>

@include('public.partials.footer')
