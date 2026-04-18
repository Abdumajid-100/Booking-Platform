<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Business;
use App\Models\Payments;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();

        $recentBookings = Booking::with(['business.type', 'service', 'payment'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(6)
            ->get();

        $recentPayments = Payments::with(['booking.business', 'booking.service'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(6)
            ->get();

        $popularBusinesses = Business::with(['type', 'services'])
            ->withCount('bookings')
            ->orderByDesc('bookings_count')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        $stats = [
            'bookings' => Booking::where('user_id', $user->id)->count(),
            'payments' => Payments::where('user_id', $user->id)->count(),
            'paid_total' => (float) Payments::where('user_id', $user->id)->where('status', 'paid')->sum('amount'),
            'pending_bookings' => Booking::where('user_id', $user->id)->where('status', 'pending')->count(),
        ];

        return view('client.dashboard', compact('user', 'recentBookings', 'recentPayments', 'popularBusinesses', 'stats'));
    }
}
