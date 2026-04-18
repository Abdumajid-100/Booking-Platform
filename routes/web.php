<?php

use App\Http\Controllers\Admin\BusinessesController;
use App\Http\Controllers\admin\BusinessesTypesController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardRedirectController;
use App\Http\Controllers\Owner\BusinessesController as OwnerBusinessesController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\ProfileController;
use App\Models\Business;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
//Wallet
Route::get('/wallet', function () {
    return view('public.wallet');
})->middleware('auth')->name('wallet');
use App\Http\Controllers\WalletController;
use App\Http\Controllers\CardController;

Route::middleware('auth')->group(function () {
    Route::post('/wallet/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit');
    Route::post('/wallet/withdraw', [WalletController::class, 'withdraw'])->name('wallet.withdraw');
    Route::post('/cards', [CardController::class, 'store'])->name('cards.store');
});

// Маршрут для отображения интерфейса чата
Route::get('/ai-chat', function () {
    return view('chat');
})->name('chat.index')->middleware('auth'); // Рекомендую защитить middleware auth
// Маршрут для обработки AJAX запросов
Route::post('/chat/message', function (Request $request) {
    $request->validate(['message' => 'required|string']);

    // --- ЗДЕСЬ БУДЕТ ЛОГИКА ПОДКЛЮЧЕНИЯ GEMINI (из предыдущих шагов) ---
    // Пока возвращаем заглушку для теста интерфейса:

    // sleep(1); // Эмуляция задержки ответа ИИ
})->name('chat.message')->middleware('auth');
Route::post('/api/chat', [AIController::class, 'chat']);
Route::get('/', function () {
    $topBusinesses = Business::with('type')
        ->withCount('bookings')
        ->orderByDesc('bookings_count')
        ->orderByDesc('id')
        ->take(3)
        ->get();
    return view('public.layouts.app', compact('topBusinesses'));
})->name('home');

Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::get('/businesses', function () {
    $businesses = Business::with(['type', 'services', 'schedules'])->latest()->get();
    return view('public.business', compact('businesses'));
})->name('business.page');

Route::get('/booking', [BookingController::class, 'create'])->name('booking.page');

Route::middleware('auth')->group(function () {
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/{booking}/payment', [BookingController::class, 'payment'])->name('booking.payment');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('businesses', BusinessesController::class);
    Route::resource('businesses-types', BusinessesTypesController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardRedirectController::class)->name('dashboard');
    Route::get('/client/dashboard', ClientDashboardController::class)
        ->middleware('role:user')
        ->name('client.dashboard');
    Route::prefix('owner')->name('owner.')->middleware('role:owner|business')->group(function () {
        Route::get('/dashboard', OwnerDashboardController::class)->name('dashboard');
        Route::resource('businesses', OwnerBusinessesController::class);
    });

    Route::redirect('/business/dashboard', '/owner/dashboard')->name('business.dashboard');

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
