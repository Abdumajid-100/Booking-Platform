<?php
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\BusinessesController as OwnerBusinessesController;
use App\Http\Controllers\ProfileController;
use App\Models\Booking;
use App\Models\Businesses;
use App\Models\Payments;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BusinessesController;
use App\Http\Controllers\admin\BusinessesTypesController;

Route::get('/', function () {
    $topBusinesses = Businesses::with('type')
        ->withCount('bookings')
        ->orderByDesc('bookings_count')
        ->orderByDesc('id')
        ->take(3)
        ->get();

    return view('public.layouts.app', compact('topBusinesses'));
})->name('home');

Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::get('/businesses', function () {
    $businesses = Businesses::with(['type', 'services', 'schedules'])
        ->latest()
        ->get();

    return view('public.business', compact('businesses'));
})->name('business.page');

Route::get('/booking', [BookingController::class, 'create'])->name('booking.page');
Route::middleware('auth')->group(function () {
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/{booking}/payment', [BookingController::class, 'payment'])->name('booking.payment');
});

Route::prefix('owner')->name('owner.')->middleware(['auth'])->group(function () {
    Route::get('/', OwnerDashboardController::class)->name('dashboard');
    Route::resource('businesses', OwnerBusinessesController::class);
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('businesses', BusinessesController::class);
    Route::resource('businesses-types', BusinessesTypesController::class);

})->middleware(['auth', 'role:admin']);
Route::get('/dashboard', function () {
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

    $popularBusinesses = Businesses::with('type')
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

    return view('dashboard', compact('user', 'recentBookings', 'recentPayments', 'popularBusinesses', 'stats'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/admin', function () {
        return view('admin.layouts.app');
    })->name('admin.layouts.app');

    Route::get('/admin/search', function (\Illuminate\Http\Request $request) {
        $query = trim((string) $request->query('q', ''));

        $pages = collect([
            ['title' => 'Admin Dashboard', 'description' => 'Open the main admin panel.', 'route' => route('admin.layouts.app')],
            ['title' => 'My Account', 'description' => 'Edit your profile, email, and password.', 'route' => route('profile.edit')],
            ['title' => 'Dashboard', 'description' => 'Open the default dashboard page.', 'route' => route('dashboard')],
            ['title' => 'Manage Bookings', 'description' => 'Open the bookings management page.', 'route' => route('bookings.manage')],
        ]);

        $results = $query === ''
            ? collect()
            : $pages->filter(function (array $page) use ($query) {
                return str_contains(strtolower($page['title']), strtolower($query))
                    || str_contains(strtolower($page['description']), strtolower($query));
            })->values();

        return view('admin.search', [
            'query' => $query,
            'results' => $results,
        ]);
    })->name('admin.search');

    Route::get('/bookings/manage', function () {
        return 'Manage bookings page';
    })->name('bookings.manage');
});
 require __DIR__.'/auth.php';
