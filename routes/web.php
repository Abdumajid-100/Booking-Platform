<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', function () {
    return view('dashboard');
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
