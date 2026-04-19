<?php
namespace App\Http\Controllers;
use App\Models\Service;
use App\Services\GeminiService;
use Illuminate\Http\Request;

class AIController extends Controller
{
public function chat(Request $request, GeminiService $gemini)
{
$text = $request->message;

// 🤖 Gemini analyze
$ai = $gemini->analyze($text);

// 💬 CHAT MODE
if ($ai['mode'] === 'chat') {
return response()->json([
'reply' => $ai['reply'],
'results' => []
]);
}

// 🔎 SEARCH MODE
$budget = $ai['budget'] ?? 999999;
$people = $ai['people'] ?? 1;
$type = $ai['type'];

$query = Service::query();

if ($type) {
$query->where('type', $type);
}

$services = $query
->where('price', '<=', $budget)
->where('capacity', '>=', $people)
->get();

// 📊 RANKING (Booking style)
$ranked = $services->map(function ($s) use ($budget) {

$score =
($s->rating * 10) +
(100 - ($s->price / max($budget,1)));

return [
'id' => $s->id,
'name' => $s->name,
'price' => $s->price,
'rating' => $s->rating,
'score' => round($score, 2),
];
})->sortByDesc('score')->values();

return response()->json([
'reply' => $ai['reply'] ?: "Я нашёл лучшие варианты 🔍",
'results' => $ranked
]);
}
}
