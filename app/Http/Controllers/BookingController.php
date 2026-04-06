<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Businesses;
use App\Models\Payments;
use App\Models\Services;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function create(): View
    {
        $businesses = Businesses::with(['type', 'services', 'schedules'])
            ->latest()
            ->get();

        return view('public.booking', compact('businesses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'business_id' => ['required', 'exists:businesses,id'],
            'service_id' => ['required', 'exists:services,id'],
            'booking_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $business = Businesses::findOrFail($validated['business_id']);
        $service = Services::where('id', $validated['service_id'])
            ->where('business_id', $business->id)
            ->firstOrFail();

        $startTime = $validated['start_time'];
        $durationMinutes = $this->durationToMinutes((string) $service->duration);
        $endTime = date('H:i', strtotime($startTime . " +{$durationMinutes} minutes"));

        $booking = DB::transaction(function () use ($request, $business, $service, $validated, $startTime, $endTime) {
            $booking = Booking::create([
                'user_id' => $request->user()->id,
                'business_id' => $business->id,
                'service_id' => $service->id,
                'booking_date' => $validated['booking_date'],
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => 'pending',
                'comment' => $validated['comment'] ?? null,
            ]);

            Payments::create([
                'user_id' => $request->user()->id,
                'booking_id' => $booking->id,
                'amount' => $service->price,
                'payment_method' => 'online',
                'status' => 'pending',
                'paid_at' => null,
            ]);

            return $booking;
        });

        return redirect()->route('booking.payment', $booking);
    }

    public function payment(Booking $booking): View
    {
        abort_unless($booking->user_id === auth()->id(), 403);

        $booking->load(['business.type', 'service', 'payment']);

        return view('public.payment', compact('booking'));
    }

    private function durationToMinutes(string $duration): int
    {
        if (preg_match('/(\d+)/', $duration, $matches)) {
            return max((int) $matches[1], 1);
        }

        return 60;
    }
}
