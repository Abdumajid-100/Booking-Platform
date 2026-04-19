<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ChatService
{
protected string $endpoint;

public function __construct()
{
$model = config('services.gemini.model', 'gemini-2.5-flash');

$this->endpoint =
"https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent";
}

public function analyze(string $text): array
{
try {

$response = Http::timeout(20)->post(
$this->endpoint . "?key=" . config('services.gemini.key'),
[
"contents" => [
[
"role" => "user",
"parts" => [
[
"text" => $this->prompt($text)
]
]
]
]
]
);

$data = $response->json();

$raw = data_get($data, 'candidates.0.content.parts.0.text');

if (!$raw) {
return $this->fallback();
}

preg_match('/\{.*\}/s', $raw, $m);

return json_decode($m[0] ?? '', true) ?? $this->fallback();

} catch (\Throwable $e) {
return $this->fallback();
}
}

private function prompt($text): string
{
return "
Ты AI помощник для бронирования.

Верни JSON:
{
\"mode\": \"chat|search\",
\"reply\": \"string\",
\"budget\": number|null,
\"people\": number|null,
\"type\": string|null
}

Текст:
".$text;
}

private function fallback(): array
{
return [
'mode' => 'chat',
'reply' => 'Попробуйте ещё раз 👋',
'budget' => null,
'people' => 1,
'type' => null
];
}
}
