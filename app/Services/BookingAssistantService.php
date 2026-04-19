<?php

namespace App\Services;

use App\Models\Business;
use Gemini\Data\Content;
use Gemini\Enums\Role;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Throwable;

class BookingAssistantService
{
    public function respond(string $message, array $history = []): array
    {
        $message = $this->sanitizeText($message);
        $history = $this->sanitizeHistory($history);

        $catalog = Business::query()
            ->with(['type', 'services', 'schedules'])
            ->withCount('bookings')
            ->get()
            ->map(fn (Business $business) => $this->normalizeBusiness($business))
            ->values();

        if ($catalog->isEmpty()) {
            return [
                'reply' => "Сейчас в каталоге ещё нет бизнесов для подбора.\n\nКогда компании и услуги появятся в системе, я смогу сравнивать их, подбирать варианты под бюджет и вести полноценный диалог по бронированию.",
                'cards' => [],
                'suggestions' => [
                    'Как выбрать бизнес для первой записи?',
                    'Что лучше уточнить перед бронированием?',
                    'Когда лучше бронировать популярные слоты?',
                ],
                'source' => 'local',
                'insights' => [
                    'budget' => null,
                    'category' => null,
                    'compare' => false,
                    'match_count' => 0,
                    'has_history' => ! empty($history),
                ],
            ];
        }

        $conversationSnapshot = $this->buildConversationSnapshot($history, $message);
        $intent = $this->detectIntent($message, $conversationSnapshot, $catalog);
        $matches = $this->selectMatches($catalog, $intent);

        if ($matches->isEmpty()) {
            $matches = $catalog
                ->sortByDesc('bookings_count')
                ->take(3)
                ->values();
        }

        $localReply = $this->buildLocalReply($intent, $matches);
        $reply = $this->tryGeminiReply($message, $history, $intent, $matches) ?? $localReply;

        return [
            'reply' => $reply,
            'cards' => $matches
                ->take(3)
                ->map(fn (array $business) => $this->toCard($business))
                ->values()
                ->all(),
            'suggestions' => $this->buildSuggestions($intent, $matches),
            'source' => $reply === $localReply ? 'local' : 'gemini',
            'insights' => [
                'budget' => $intent['budget'],
                'category' => $intent['category_label'],
                'compare' => $intent['compare'],
                'match_count' => $matches->count(),
                'has_history' => ! empty($history),
            ],
        ];
    }

    private function sanitizeHistory(array $history): array
    {
        return collect($history)
            ->filter(fn ($item) => is_array($item))
            ->map(function (array $item) {
                $role = ($item['role'] ?? null) === 'assistant' ? 'assistant' : 'user';
                $content = $this->sanitizeText((string) ($item['content'] ?? ''));

                if ($content === '') {
                    return null;
                }

                return [
                    'role' => $role,
                    'content' => Str::limit($content, 2000, ''),
                ];
            })
            ->filter()
            ->take(-10)
            ->values()
            ->all();
    }

    private function sanitizeText(string $value): string
    {
        return trim((string) preg_replace('/\s+/u', ' ', $value));
    }

    private function buildConversationSnapshot(array $history, string $message): string
    {
        $parts = collect($history)
            ->pluck('content')
            ->push($message)
            ->take(-8)
            ->values();

        return $parts->implode(' ');
    }

    private function normalizeBusiness(Business $business): array
    {
        $services = $business->services
            ->sortBy('price')
            ->map(fn ($service) => [
                'name' => (string) $service->name,
                'price' => $service->price !== null ? (float) $service->price : null,
                'duration' => (string) $service->duration,
            ])
            ->values();

        $prices = $services
            ->pluck('price')
            ->filter(fn ($price) => $price !== null)
            ->values();

        $type = trim((string) optional($business->type)->name);
        $serviceNames = $services->pluck('name')->implode(' ');
        $description = trim((string) $business->description);
        $address = trim((string) $business->address);

        return [
            'id' => $business->id,
            'name' => trim((string) $business->name),
            'type' => $type,
            'description' => $description,
            'address' => $address,
            'phone' => trim((string) $business->phone),
            'bookings_count' => (int) $business->bookings_count,
            'services_count' => $services->count(),
            'services' => $services->all(),
            'min_price' => $prices->min(),
            'avg_price' => $prices->isNotEmpty() ? round((float) $prices->avg(), 2) : null,
            'max_price' => $prices->max(),
            'schedule_summary' => $this->summarizeSchedule($business->schedules),
            'image_url' => $business->image ? asset('storage/' . $business->image) : null,
            'search_blob' => mb_strtolower(trim(implode(' ', array_filter([
                $business->name,
                $type,
                $description,
                $address,
                $serviceNames,
            ])))),
        ];
    }

    private function detectIntent(string $message, string $conversationSnapshot, Collection $catalog): array
    {
        $normalizedMessage = mb_strtolower($message);
        $snapshotNormalized = mb_strtolower($conversationSnapshot);

        $tokens = collect(preg_split('/[^\p{L}\p{N}]+/u', $snapshotNormalized, -1, PREG_SPLIT_NO_EMPTY))
            ->filter(fn ($token) => mb_strlen((string) $token) >= 2)
            ->unique()
            ->values();

        $mentionedBusinesses = $catalog
            ->filter(fn (array $business) => $this->matchesBusinessName($snapshotNormalized, $business['name']))
            ->values();

        $types = $catalog
            ->pluck('type')
            ->filter()
            ->unique()
            ->values();

        $categoryLabel = $types->first(
            fn (string $type) => Str::contains($snapshotNormalized, mb_strtolower($type))
        );

        $categoryHint = $categoryLabel ? mb_strtolower($categoryLabel) : null;

        if (! $categoryHint) {
            $synonyms = [
                'барбер' => ['барбер', 'barber', 'стрижк', 'бород'],
                'салон' => ['салон', 'маник', 'педик', 'бров', 'ресниц', 'космет'],
                'клиник' => ['клиник', 'врач', 'мед', 'стомат', 'леч'],
                'авто' => ['авто', 'мойк', 'шиномонт', 'ремонт', 'диагност'],
                'фитнес' => ['фитнес', 'спорт', 'тренаж', 'зал', 'тренир'],
                'курс' => ['курс', 'обуч', 'школ', 'урок', 'репет'],
            ];

            foreach ($synonyms as $label => $words) {
                if (! $this->containsAny($snapshotNormalized, $words)) {
                    continue;
                }

                $categoryHint = $label;
                $categoryLabel = $types->first(
                    fn (string $type) => Str::contains(mb_strtolower($type), $label)
                ) ?? Str::title($label);

                break;
            }
        }

        return [
            'budget' => $this->extractBudget($conversationSnapshot),
            'compare' => $this->containsAny($snapshotNormalized, ['сравн', 'compare', 'vs', 'versus']),
            'needs_booking_help' => $this->containsAny($snapshotNormalized, ['брон', 'запис', 'оформ', 'выбрат', 'совет', 'подбер', 'посовет']),
            'greeting' => $this->containsAny($normalizedMessage, ['привет', 'здравств', 'hello', 'hi']),
            'thanks' => $this->containsAny($normalizedMessage, ['спасибо', 'благодар', 'круто', 'отлично', 'супер']),
            'goodbye' => $this->containsAny($normalizedMessage, ['пока', 'до свидания', 'увидимся', 'bye']),
            'follow_up' => $this->containsAny($normalizedMessage, ['дальше', 'следующ', 'подешевле', 'дороже', 'побыстрее', 'ещё', 'еще', 'другой вариант', 'что потом', 'как дальше']),
            'current_message' => $message,
            'mentioned_businesses' => $mentionedBusinesses->pluck('id')->all(),
            'category_label' => $categoryLabel,
            'category_hint' => $categoryHint,
            'tokens' => $tokens->all(),
        ];
    }

    private function selectMatches(Collection $catalog, array $intent): Collection
    {
        $mentionedIds = collect($intent['mentioned_businesses']);

        if ($intent['compare'] && $mentionedIds->count() >= 2) {
            return $catalog
                ->filter(fn (array $business) => $mentionedIds->contains($business['id']))
                ->sortByDesc('bookings_count')
                ->take(3)
                ->values();
        }

        return $catalog
            ->map(function (array $business) use ($intent, $mentionedIds) {
                $score = 0;

                if ($mentionedIds->contains($business['id'])) {
                    $score += 100;
                }

                if ($intent['category_hint'] && Str::contains($business['search_blob'], $intent['category_hint'])) {
                    $score += 45;
                }

                foreach ($intent['tokens'] as $token) {
                    if (Str::contains($business['search_blob'], $token)) {
                        $score += 7;
                    }
                }

                if ($intent['budget']) {
                    if ($business['min_price'] !== null && $business['min_price'] <= $intent['budget']) {
                        $score += 35;
                    } elseif ($business['min_price'] !== null) {
                        $score -= 18;
                    }

                    if ($business['avg_price'] !== null && $business['avg_price'] <= $intent['budget']) {
                        $score += 12;
                    }
                }

                if ($intent['follow_up'] && Str::contains(mb_strtolower($intent['current_message']), 'подешевле')) {
                    $score += $business['min_price'] !== null ? max(30 - ((int) round($business['min_price'] / 10000)), 0) : 0;
                }

                $score += min($business['bookings_count'] * 3, 24);
                $score += min($business['services_count'] * 2, 12);

                return [
                    ...$business,
                    'score' => $score,
                ];
            })
            ->sortByDesc('score')
            ->take(5)
            ->values();
    }

    private function buildLocalReply(array $intent, Collection $matches): string
    {
        if ($intent['thanks']) {
            $best = $matches->first();

            if ($best) {
                return "Пожалуйста. Если хотите, могу сразу продолжить и помочь по {$best['name']} или подобрать ещё более подходящий вариант.";
            }

            return 'Пожалуйста. Могу продолжить подбор, сравнение или подсказать следующий шаг по бронированию.';
        }

        if ($intent['goodbye']) {
            return 'Хорошо. Когда захотите продолжить, просто напишите категорию, бюджет или названия бизнесов для сравнения.';
        }

        if ($intent['greeting'] && ! $intent['needs_booking_help'] && ! $intent['compare'] && ! $intent['budget']) {
            return "Я на связи. Можем вести обычный диалог: уточняйте бюджет, просите варианты подешевле, спрашивайте, что лучше выбрать и как оформить бронирование.\n\nНапример: \"подбери барбершоп до 150000\", \"а есть дешевле?\" или \"что делать дальше после выбора?\"";
        }

        if ($intent['compare']) {
            return $this->buildComparisonReply($intent, $matches);
        }

        if ($intent['follow_up'] && $this->containsAny(mb_strtolower($intent['current_message']), ['что дальше', 'как дальше', 'что потом'])) {
            $best = $matches->first();
            $businessName = $best['name'] ?? 'подходящего бизнеса';

            return "Дальше логика простая:\n- выберите {$businessName} или другой понравившийся вариант\n- откройте страницу бронирования\n- выберите услугу, дату и время\n- подтвердите запись и переходите к оплате, если она нужна\n\nЕсли хотите, я могу ещё сузить выбор перед этим.";
        }

        if ($intent['needs_booking_help'] || $intent['budget'] || $intent['category_label'] || $intent['follow_up']) {
            return $this->buildRecommendationReply($intent, $matches);
        }

        return "Могу вести диалог в обычном режиме и помнить последние сообщения.\n\nСпросите меня о подборе бизнеса, сравнении вариантов, цене, услугах или следующем шаге по бронированию.";
    }

    private function buildComparisonReply(array $intent, Collection $matches): string
    {
        $bestPrice = $matches
            ->filter(fn (array $business) => $business['min_price'] !== null)
            ->sortBy('min_price')
            ->first();

        $bestPopularity = $matches->sortByDesc('bookings_count')->first();
        $bestChoice = $matches->sortByDesc('services_count')->first();

        $intro = $intent['category_label']
            ? "Сравнил варианты по категории {$intent['category_label']}."
            : 'Сравнил подходящие варианты из каталога.';

        $lines = [$intro, ''];

        foreach ($matches->take(3) as $business) {
            $lines[] = "- {$business['name']} — {$this->businessMiniSummary($business)}";
        }

        $lines[] = '';

        if ($bestPrice) {
            $lines[] = "По цене выгоднее начать с {$bestPrice['name']} ({$this->formatMoney($bestPrice['min_price'])} от минимальной услуги).";
        }

        if ($bestPopularity) {
            $lines[] = "По популярности лидирует {$bestPopularity['name']} ({$bestPopularity['bookings_count']} бронирований в системе).";
        }

        if ($bestChoice) {
            $lines[] = "По выбору услуг сильнее {$bestChoice['name']} ({$bestChoice['services_count']} услуг).";
        }

        $lines[] = '';
        $lines[] = 'Если хотите, следующим сообщением могу сравнить их именно по цене, удобству графика или разнообразию услуг.';

        return implode("\n", $lines);
    }

    private function buildRecommendationReply(array $intent, Collection $matches): string
    {
        $headlineParts = [$intent['follow_up'] ? 'Продолжаю подбор' : 'Подобрал варианты'];

        if ($intent['category_label']) {
            $headlineParts[] = 'по категории ' . $intent['category_label'];
        }

        if ($intent['budget']) {
            $headlineParts[] = 'до ' . $this->formatMoney($intent['budget']);
        }

        $lines = [implode(' ', $headlineParts) . '.', ''];

        foreach ($matches->take(3) as $business) {
            $lines[] = "- {$business['name']} — {$this->businessMiniSummary($business)}";
        }

        $best = $matches->first();

        if ($best) {
            $lines[] = '';
            $lines[] = "Сначала смотрите {$best['name']}, если нужен наиболее сбалансированный вариант по цене, набору услуг и востребованности.";
        }

        if ($intent['budget']) {
            $overBudget = $matches->every(
                fn (array $business) => $business['min_price'] === null || $business['min_price'] > $intent['budget']
            );

            if ($overBudget) {
                $lines[] = 'С таким бюджетом точных попаданий мало, поэтому я показал самые близкие варианты по каталогу.';
            }
        }

        if ($intent['follow_up'] && $this->containsAny(mb_strtolower($intent['current_message']), ['подешевле', 'дешевле'])) {
            $lines[] = 'Я сместил акцент в сторону более доступных вариантов, сохранив категорию из предыдущего диалога.';
        }

        $lines[] = '';
        $lines[] = 'Можете продолжить разговор короткой репликой: "а дешевле есть?", "сравни первые два" или "что дальше делать?".';

        return implode("\n", $lines);
    }

    private function businessMiniSummary(array $business): string
    {
        $parts = [];

        if ($business['type']) {
            $parts[] = $business['type'];
        }

        if ($business['min_price'] !== null) {
            $parts[] = 'от ' . $this->formatMoney($business['min_price']);
        }

        $parts[] = $business['services_count'] . ' услуг';
        $parts[] = $business['schedule_summary'];

        return implode(', ', $parts);
    }

    private function toCard(array $business): array
    {
        return [
            'id' => $business['id'],
            'name' => $business['name'],
            'type' => $business['type'] ?: 'Бизнес',
            'description' => Str::limit($business['description'] ?: 'Описание пока не заполнено.', 140),
            'address' => $business['address'] ?: 'Адрес не указан',
            'price' => $business['min_price'] !== null ? 'от ' . $this->formatMoney($business['min_price']) : 'Цена по запросу',
            'services_count' => $business['services_count'],
            'bookings_count' => $business['bookings_count'],
            'schedule' => $business['schedule_summary'],
            'image_url' => $business['image_url'],
            'service_names' => collect($business['services'])->pluck('name')->take(3)->values()->all(),
        ];
    }

    private function buildSuggestions(array $intent, Collection $matches): array
    {
        $suggestions = collect([
            'Как выбрать лучший бизнес для бронирования?',
            'Сравни самые популярные варианты',
            'На что смотреть перед записью?',
            'Что делать дальше после выбора?',
        ]);

        if ($intent['category_label']) {
            $suggestions->prepend('Сравни лучшие ' . mb_strtolower($intent['category_label']));
        }

        if ($intent['budget']) {
            $suggestions->prepend('Покажи ещё варианты до ' . (int) $intent['budget']);
        }

        if ($matches->isNotEmpty()) {
            $suggestions->prepend('Сравни первые два варианта');
            $suggestions->prepend('Есть что-то дешевле?');
        }

        return $suggestions
            ->unique()
            ->take(4)
            ->values()
            ->all();
    }

    private function tryGeminiReply(string $message, array $history, array $intent, Collection $matches): ?string
    {
        $apiKey = config('services.gemini.key');

        if (! is_string($apiKey) || trim($apiKey) === '') {
            return null;
        }

        $context = [
            'intent' => [
                'budget' => $intent['budget'],
                'category' => $intent['category_label'],
                'compare' => $intent['compare'],
                'follow_up' => $intent['follow_up'],
            ],
            'candidates' => $matches
                ->take(4)
                ->map(fn (array $business) => [
                    'name' => $business['name'],
                    'type' => $business['type'],
                    'description' => $business['description'],
                    'address' => $business['address'],
                    'phone' => $business['phone'],
                    'min_price' => $business['min_price'],
                    'avg_price' => $business['avg_price'],
                    'services_count' => $business['services_count'],
                    'bookings_count' => $business['bookings_count'],
                    'services' => collect($business['services'])->take(5)->values()->all(),
                    'schedule' => $business['schedule_summary'],
                ])
                ->values()
                ->all(),
        ];

        $historyContents = collect($history)
            ->map(function (array $item) {
                $role = $item['role'] === 'assistant' ? Role::MODEL : Role::USER;

                return Content::parse($item['content'], $role);
            })
            ->values()
            ->all();

        $systemInstruction = implode("\n\n", [
            'Ты разговорный ИИ-помощник платформы бронирования BroNix.',
            'Отвечай только на русском языке, естественно и как в обычном диалоге.',
            'Помни историю разговора и продолжай контекст без повторного расспроса, если он уже понятен.',
            'Используй только факты из текущего JSON-контекста. Не выдумывай рейтинги, отзывы, скидки, свободные окна и адреса.',
            'Если данных недостаточно, честно скажи об этом и предложи следующий полезный вопрос.',
            'Сначала дай короткий вывод, потом 2-4 практичных пункта. Если пользователь просто уточняет, отвечай короче.',
            'Если есть явный лучший вариант, назови его и почему.',
            'JSON-контекст каталога: ' . json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ]);

        try {
            $chat = \Gemini::client($apiKey)
                ->generativeModel(model: 'gemini-2.0-flash')
                ->withSystemInstruction(Content::parse($systemInstruction))
                ->startChat(history: $historyContents);

            $reply = trim((string) $chat->sendMessage($message)->text());

            return $reply !== '' ? $reply : null;
        } catch (Throwable) {
            return null;
        }
    }

    private function extractBudget(string $message): ?int
    {
        preg_match_all('/\d[\d\s,.]*/u', $message, $matches);

        foreach ($matches[0] ?? [] as $rawValue) {
            $normalized = preg_replace('/[^\d]/', '', (string) $rawValue);

            if ($normalized === '') {
                continue;
            }

            $value = (int) $normalized;

            if ($value > 0) {
                return $value;
            }
        }

        return null;
    }

    private function matchesBusinessName(string $normalizedMessage, string $businessName): bool
    {
        $normalizedName = mb_strtolower($businessName);

        if ($normalizedName !== '' && Str::contains($normalizedMessage, $normalizedName)) {
            return true;
        }

        $tokens = collect(preg_split('/[^\p{L}\p{N}]+/u', $normalizedName, -1, PREG_SPLIT_NO_EMPTY))
            ->filter(fn ($token) => mb_strlen((string) $token) >= 4)
            ->values();

        if ($tokens->isEmpty()) {
            return false;
        }

        $matched = $tokens->filter(
            fn ($token) => Str::contains($normalizedMessage, (string) $token)
        )->count();

        return $tokens->count() >= 2 ? $matched >= 2 : $matched >= 1;
    }

    private function summarizeSchedule(Collection $schedules): string
    {
        if ($schedules->isEmpty()) {
            return 'График не указан';
        }

        $dayLabels = [
            'monday' => 'Пн',
            'tuesday' => 'Вт',
            'wednesday' => 'Ср',
            'thursday' => 'Чт',
            'friday' => 'Пт',
            'saturday' => 'Сб',
            'sunday' => 'Вс',
        ];

        $items = collect($dayLabels)
            ->map(function (string $label, string $dayKey) use ($schedules) {
                $row = $schedules->first(
                    fn ($schedule) => mb_strtolower((string) $schedule->day_of_week) === $dayKey
                );

                if (! $row || $row->is_day_off) {
                    return null;
                }

                $start = $row->start_time ? Str::substr((string) $row->start_time, 0, 5) : null;
                $end = $row->end_time ? Str::substr((string) $row->end_time, 0, 5) : null;

                if (! $start || ! $end || ($start === '00:00' && $end === '00:00')) {
                    return null;
                }

                return $label . ' ' . $start . '-' . $end;
            })
            ->filter()
            ->values();

        if ($items->isEmpty()) {
            return 'График не указан';
        }

        $preview = $items->take(3)->implode(', ');

        if ($items->count() > 3) {
            return $preview . ', ещё ' . ($items->count() - 3) . ' дн.';
        }

        return $preview;
    }

    private function containsAny(string $haystack, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (Str::contains($haystack, $needle)) {
                return true;
            }
        }

        return false;
    }

    private function formatMoney(float|int $amount): string
    {
        return number_format((float) $amount, 0, '.', ' ') . ' сум';
    }
}
