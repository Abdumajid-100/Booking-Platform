<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Business;
use App\Models\Service;
use App\Services\BookingAssistantService;
use App\Services\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate(['message' => 'required|string']);
        $userMessage = $request->input('message');

        // 1. Получаем данные из ваших таблиц для анализа
        // Мы берем данные заранее, чтобы передать их ИИ как "базу знаний"
        $businesses = Business::all();

        $dataContext = "Вот список реальных бизнесов из моей базы данных:\n";
        foreach ($businesses as $biz) {
            // Подставьте свои названия колонок (например: $biz->title, $biz->price)
            $dataContext .= "- Название: {$biz->name}, Инвестиции: {$biz->description}$, Окупаемость: {$biz->address} мес, Прибыль: {$biz->image}$/мес.\n";
        }

        // 2. Формируем системную роль для ИИ
        $systemPrompt = "Ты — продвинутый бизнес-аналитик в проекте пользователя.
    Твоя задача: отвечать на вопросы и делать сравнительный анализ.
    ИСПОЛЬЗУЙ ТОЛЬКО ЭТИ ДАННЫЕ ДЛЯ АНАЛИЗА:
    {$dataContext}

    Если пользователь просит 'сделать анализ' или 'выбрать лучшие', сравни показатели инвестиций и окупаемости из списка выше.
    Отвечай вежливо и профессионально на языке пользователя.";

        try {
            // 3. Запрос к реальному Gemini API
            // Мы отправляем и инструкцию с данными, и само сообщение пользователя
            $result = Gemini::geminiPro()->generateContent([
                $systemPrompt,
                "Вопрос клиента: " . $userMessage
            ]);

            $aiResponse = $result->text();

            return response()->json([
                'status' => 'success',
                'message' => $aiResponse
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ошибка API: ' . $e->getMessage()
            ], 500);
        }
    }
}
