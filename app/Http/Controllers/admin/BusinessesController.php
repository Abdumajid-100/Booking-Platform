<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Businesses;
use App\Models\BusinessesTypes;
use App\Models\Schedules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BusinessesController extends Controller
{
    public function index()
    {
        $businesses = Businesses::with('type', 'owner', 'schedules')->get();
        return view('admin.businesses.index', compact('businesses'));
    }

    public function create(Request $request)
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $types = BusinessesTypes::query()->orderBy('name')->get();
        return view('admin.businesses.create', compact('types'));
    }

    public function store(Request $request)
    {
        $userId = $request->user()?->id;
        if (!$userId) {
            return redirect()->route('login');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'businesses_type_id' => 'required|exists:businesses_types,id',
            'image' => 'nullable|image|max:2048',
            'schedule.*.start' => 'nullable|date_format:H:i',
            'schedule.*.end' => 'nullable|date_format:H:i',
        ]);

        $data['user_id'] = $userId;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('business_images', 'public');
        }

        $business = Businesses::create($data);

        if ($request->has('schedule')) {
            foreach ($request->schedule as $day => $scheduleData) {
                $this->storeScheduleRow($business->id, $day, $scheduleData);
            }
        }

        return redirect()->route('admin.businesses.index')
            ->with('success', 'Бизнес добавлен!');
    }

    public function edit(Businesses $business)
    {
        $types = BusinessesTypes::query()->orderBy('name')->get();
        $business->load('schedules');

        return view('admin.businesses.edit', compact('business', 'types'));
    }

    public function update(Request $request, Businesses $business)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'businesses_type_id' => 'required|exists:businesses_types,id',
            'image' => 'nullable|image|max:2048',
            'schedule.*.start' => 'nullable|date_format:H:i',
            'schedule.*.end' => 'nullable|date_format:H:i',
        ]);

        if ($request->hasFile('image')) {
            if ($business->image) {
                Storage::disk('public')->delete($business->image);
            }

            $data['image'] = $request->file('image')->store('business_images', 'public');
        }

        $business->update($data);

        if ($request->has('schedule')) {
            foreach ($request->schedule as $day => $scheduleData) {
                $this->upsertScheduleRow($business, $day, $scheduleData);
            }
        }

        return redirect()->route('admin.businesses.index')
            ->with('success', 'Бизнес обновлен!');
    }

    public function destroy(Businesses $business)
    {
        if ($business->image) {
            Storage::disk('public')->delete($business->image);
        }

        $business->schedules()->delete();
        $business->delete();

        return redirect()->route('admin.businesses.index')
            ->with('success', 'Бизнес удален!');
    }

    public function show(Businesses $business)
    {
        $business->load('type', 'owner', 'schedules');

        return view('admin.businesses.show', compact('business'));
    }

    private function storeScheduleRow(int $businessId, string $day, array $scheduleData): void
    {
        $startTime = $this->normalizeTime($scheduleData['start'] ?? null);
        $endTime = $this->normalizeTime($scheduleData['end'] ?? null);
        $hasTimeRange = (bool) ($startTime && $endTime);
        // If hours are not provided, save day as day off by default.
        $isDayOff = !$hasTimeRange;

        Schedules::create([
            'business_id' => $businessId,
            'day_of_week' => $day,
            'start_time' => $isDayOff ? '00:00' : $startTime,
            'end_time' => $isDayOff ? '00:00' : $endTime,
            'is_day_off' => $isDayOff,
        ]);
    }

    private function upsertScheduleRow(Businesses $business, string $day, array $scheduleData): void
    {
        $schedule = $business->schedules()->firstOrNew(['day_of_week' => $day]);

        $startTime = $this->normalizeTime($scheduleData['start'] ?? null);
        $endTime = $this->normalizeTime($scheduleData['end'] ?? null);
        $hasTimeRange = (bool) ($startTime && $endTime);
        // If hours are not provided, keep day as day off by default.
        $isDayOff = !$hasTimeRange;

        $schedule->business_id = $business->id;
        $schedule->start_time = $isDayOff ? '00:00' : $startTime;
        $schedule->end_time = $isDayOff ? '00:00' : $endTime;
        $schedule->is_day_off = $isDayOff;
        $schedule->save();
    }

    private function normalizeTime(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        $value = trim($value);
        if ($value === '') {
            return null;
        }

        // Accept only valid HH:MM or HH:MM:SS and store as HH:MM.
        if (!preg_match('/^\\d{2}:\\d{2}(:\\d{2})?$/', $value)) {
            return null;
        }

        $time = substr($value, 0, 5);
        [$hours, $minutes] = array_map('intval', explode(':', $time));

        if ($hours < 0 || $hours > 23 || $minutes < 0 || $minutes > 59) {
            return null;
        }

        return $time;
    }
}
