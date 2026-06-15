<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Kitchen\KitchenController;
use App\Http\Controllers\POS\POSController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth'])->get('/dashboard', function () {
    $user = Auth::user();

    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'cashier' => redirect()->route('pos.index'),
        'barista' => redirect()->route('kitchen.index'),
        default => abort(403),
    };
})->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Temporary placeholders to keep sidebar links functional until dedicated pages are added.
        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('staff', StaffController::class)->except(['show']);
        Route::patch('/staff/{staff}/toggle-active', [StaffController::class, 'toggleActive'])->name('staff.toggle-active');
        Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/reports', fn() => view('admin.report.index'))->name('reports.index');
    });
Route::middleware(['auth', 'role:cashier'])
    ->prefix('pos')
    ->name('pos.')
    ->group(function () {
        Route::get('/', [POSController::class, 'index'])->name('index');
        Route::post('/orders', [POSController::class, 'storeOrder'])->name('orders.store');
        Route::get('/orders', [POSController::class, 'orders'])->name('orders');
        Route::get('/history', [POSController::class, 'history'])->name('history');
    });

Route::middleware(['auth'])
    ->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

Route::middleware(['auth', 'role:barista'])
    ->prefix('kitchen')
    ->name('kitchen.')
    ->group(function () {
        Route::get('/', [KitchenController::class, 'index'])->name('index');
        Route::patch('/{order}', [KitchenController::class, 'update'])->name('update');
    });

require __DIR__ . '/auth.php';
