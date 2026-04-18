<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    // 💰 Пополнение
    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        $user = auth()->user();

        DB::transaction(function () use ($user, $request) {
            // если кошелька нет — создаём
            $wallet = $user->wallet ?? Wallet::create([
                'user_id' => $user->id,
                'balance' => 0
            ]);

            // пополняем баланс
            $wallet->increment('balance', $request->amount);

            // транзакция
            Transaction::create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'type' => 'deposit',
                'status' => 'success'
            ]);
        });

        return back()->with('success', 'Баланс пополнен');
    }

    // 🏦 Вывод
    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        $user = auth()->user();
        $wallet = $user->wallet;

        if (!$wallet || $wallet->balance < $request->amount) {
            return back()->with('error', 'Недостаточно средств');
        }

        DB::transaction(function () use ($user, $wallet, $request) {
            // списываем
            $wallet->decrement('balance', $request->amount);

            // транзакция
            Transaction::create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'type' => 'withdraw',
                'status' => 'pending'
            ]);
        });

        return back()->with('success', 'Заявка на вывод создана');
    }
}
