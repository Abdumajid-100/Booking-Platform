<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payments;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();
        $businessIds = $user->businesses()->pluck('id');

        $recentBookings = Booking::with(['business.type', 'service', 'user', 'payment'])
            ->whereIn('business_id', $businessIds)
            ->latest()
            ->take(8)
            ->get();

        $recentPayments = Payments::with(['booking.business', 'booking.service', 'user'])
            ->whereHas('booking', function ($query) use ($businessIds) {
                $query->whereIn('business_id', $businessIds);
            })
            ->latest()
            ->take(8)
            ->get();

        $stats = [
            'businesses' => $user->businesses()->count(),
            'bookings' => Booking::whereIn('business_id', $businessIds)->count(),
            'pending_bookings' => Booking::whereIn('business_id', $businessIds)->where('status', 'pending')->count(),
            'paid_total' => (float) Payments::whereHas('booking', function ($query) use ($businessIds) {
                $query->whereIn('business_id', $businessIds);
            })->where('status', 'paid')->sum('amount'),
        ];

        return view('owner.dashboard', compact('user', 'recentBookings', 'recentPayments', 'stats'));
    }
}
