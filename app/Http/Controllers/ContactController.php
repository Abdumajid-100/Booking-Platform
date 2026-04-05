<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class ContactController extends Controller
{
    public function send(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $botToken = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');

        if (!$botToken || !$chatId) {
            return back()
                ->withInput()
                ->with('contact_error', 'Telegram еще не настроен. Добавьте TELEGRAM_BOT_TOKEN и TELEGRAM_CHAT_ID в .env.');
        }

        $text = implode("\n", [
            '<b>Новая заявка с сайта BroNix</b>',
            '',
            '<b>Имя:</b> ' . e($validated['name']),
            '<b>Email:</b> ' . e($validated['email']),
            '<b>Тема:</b> ' . e($validated['subject']),
            '<b>Сообщение:</b>',
            e(Str::of($validated['message'])->trim()->toString()),
        ]);

        try {
            $response = Http::asForm()->post(
                "https://api.telegram.org/bot{$botToken}/sendMessage",
                [
                    'chat_id' => $chatId,
                    'text' => $text,
                    'parse_mode' => 'HTML',
                    'disable_web_page_preview' => true,
                ]
            );

            if (!$response->successful()) {
                return back()
                    ->withInput()
                    ->with('contact_error', 'Не удалось отправить сообщение в Telegram. Проверьте bot token и chat ID.');
            }
        } catch (Throwable) {
            return back()
                ->withInput()
                ->with('contact_error', 'Ошибка запроса к Telegram. Попробуйте еще раз после настройки бота.');
        }

        return back()->with('contact_success', 'Сообщение отправлено. Мы свяжемся с вами в ближайшее время.');
    }
}
