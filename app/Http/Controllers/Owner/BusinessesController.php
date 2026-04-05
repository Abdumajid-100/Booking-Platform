<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Businesses;
use App\Models\BusinessesTypes;
use App\Models\Schedules;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class BusinessesController extends Controller
{
    public function index(Request $request): View
    {
        $businesses = Businesses::with(['type', 'schedules', 'services'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('owner.businesses.index', compact('businesses'));
    }

    public function create(): View
    {
        $types = BusinessesTypes::query()->orderBy('name')->get();

        return view('owner.businesses.create', compact('types'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'businesses_type_id' => 'required|exists:businesses_types,id',
            'image' => 'nullable|image|max:2048',
            'schedule.*.start' => 'nullable|date_format:H:i',
            'schedule.*.end' => 'nullable|date_format:H:i',
            'services' => 'nullable|array',
            'services.*.name' => 'nullable|string|max:255',
            'services.*.price' => 'nullable|numeric|min:0',
            'services.*.duration' => 'nullable|string|max:100',
        ]);

        $validator->after(function ($validator) use ($request) {
            $this->validateServicesInput($validator, $request->input('services', []));
        });

        $data = $validator->validate();
        $data['user_id'] = $request->user()->id;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('business_images', 'public');
        }

        $business = Businesses::create($data);

        if ($request->user() && !$request->user()->hasRole('owner')) {
            $request->user()->assignRole('owner');
        }

        foreach ($request->input('schedule', []) as $day => $scheduleData) {
            $this->storeScheduleRow($business->id, $day, $scheduleData);
        }

        $this->syncServices($business, $request->input('services', []));

        return redirect()->route('owner.businesses.index')->with('success', 'Бизнес успешно добавлен.');
    }

    public function show(Request $request, Businesses $business): View
    {
        $business = $this->ownedBusiness($request, $business);
        $business->load(['type', 'schedules', 'services']);

        return view('owner.businesses.show', compact('business'));
    }

    public function edit(Request $request, Businesses $business): View
    {
        $business = $this->ownedBusiness($request, $business);
        $business->load(['schedules', 'services']);
        $types = BusinessesTypes::query()->orderBy('name')->get();

        return view('owner.businesses.edit', compact('business', 'types'));
    }

    public function update(Request $request, Businesses $business): RedirectResponse
    {
        $business = $this->ownedBusiness($request, $business);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'businesses_type_id' => 'required|exists:businesses_types,id',
            'image' => 'nullable|image|max:2048',
            'schedule.*.start' => 'nullable|date_format:H:i',
            'schedule.*.end' => 'nullable|date_format:H:i',
            'services' => 'nullable|array',
            'services.*.id' => 'nullable|integer|exists:services,id',
            'services.*.name' => 'nullable|string|max:255',
            'services.*.price' => 'nullable|numeric|min:0',
            'services.*.duration' => 'nullable|string|max:100',
        ]);

        $validator->after(function ($validator) use ($request) {
            $this->validateServicesInput($validator, $request->input('services', []));
        });

        $data = $validator->validate();

        if ($request->hasFile('image')) {
            if ($business->image) {
                Storage::disk('public')->delete($business->image);
            }

            $data['image'] = $request->file('image')->store('business_images', 'public');
        }

        $business->update($data);

        foreach ($request->input('schedule', []) as $day => $scheduleData) {
            $this->upsertScheduleRow($business, $day, $scheduleData);
        }

        $this->syncServices($business, $request->input('services', []));

        return redirect()->route('owner.businesses.index')->with('success', 'Бизнес успешно обновлен.');
    }

    public function destroy(Request $request, Businesses $business): RedirectResponse
    {
        $business = $this->ownedBusiness($request, $business);

        if ($business->image) {
            Storage::disk('public')->delete($business->image);
        }

        $business->schedules()->delete();
        $business->services()->delete();
        $business->delete();

        return redirect()->route('owner.businesses.index')->with('success', 'Бизнес успешно удален.');
    }

    private function ownedBusiness(Request $request, Businesses $business): Businesses
    {
        abort_unless($business->user_id === $request->user()->id, 403);

        return $business;
    }

    private function storeScheduleRow(int $businessId, string $day, array $scheduleData): void
    {
        $startTime = $this->normalizeTime($scheduleData['start'] ?? null);
        $endTime = $this->normalizeTime($scheduleData['end'] ?? null);
        $hasTimeRange = (bool) ($startTime && $endTime);
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
        if ($value === '' || !preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $value)) {
            return null;
        }

        $time = substr($value, 0, 5);
        [$hours, $minutes] = array_map('intval', explode(':', $time));

        if ($hours < 0 || $hours > 23 || $minutes < 0 || $minutes > 59) {
            return null;
        }

        return $time;
    }

    private function validateServicesInput(\Illuminate\Validation\Validator $validator, array $services): void
    {
        foreach ($services as $index => $service) {
            $name = trim((string) ($service['name'] ?? ''));
            $price = trim((string) ($service['price'] ?? ''));
            $duration = trim((string) ($service['duration'] ?? ''));
            $hasAnyValue = $name !== '' || $price !== '' || $duration !== '';

            if (!$hasAnyValue) {
                continue;
            }

            if ($name === '') {
                $validator->errors()->add("services.$index.name", 'Укажите название услуги.');
            }

            if ($price === '') {
                $validator->errors()->add("services.$index.price", 'Укажите стоимость услуги.');
            }

            if ($duration === '') {
                $validator->errors()->add("services.$index.duration", 'Укажите длительность услуги.');
            }
        }
    }

    private function syncServices(Businesses $business, array $servicesInput): void
    {
        $services = collect($servicesInput)
            ->map(function (array $service) {
                return [
                    'id' => $service['id'] ?? null,
                    'name' => trim((string) ($service['name'] ?? '')),
                    'price' => trim((string) ($service['price'] ?? '')),
                    'duration' => trim((string) ($service['duration'] ?? '')),
                ];
            })
            ->filter(function (array $service) {
                return $service['name'] !== '' && $service['price'] !== '' && $service['duration'] !== '';
            })
            ->values();

        $existingIds = $business->services()->pluck('id')->all();
        $keptIds = [];

        foreach ($services as $serviceData) {
            $serviceId = $serviceData['id'] ? (int) $serviceData['id'] : null;
            $payload = [
                'name' => $serviceData['name'],
                'price' => $serviceData['price'],
                'duration' => $serviceData['duration'],
            ];

            if ($serviceId) {
                $service = $business->services()->whereKey($serviceId)->first();

                if ($service) {
                    $service->update($payload);
                    $keptIds[] = $service->id;
                    continue;
                }
            }

            $created = $business->services()->create($payload);
            $keptIds[] = $created->id;
        }

        $idsToDelete = array_diff($existingIds, $keptIds);
        if ($idsToDelete !== []) {
            $business->services()->whereIn('id', $idsToDelete)->delete();
        }
    }
}
