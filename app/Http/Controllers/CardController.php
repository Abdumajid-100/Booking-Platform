<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Card;

class CardController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'card_number' => 'required',
            'holder_name' => 'required',
            'expiry' => 'required'
        ]);

        Card::create([
            'user_id' => auth()->id(),
            'card_number' => $request->card_number,
            'holder_name' => $request->holder_name,
            'expiry' => $request->expiry,
        ]);

        return back()->with('success', 'Карта добавлена');
    }
}
