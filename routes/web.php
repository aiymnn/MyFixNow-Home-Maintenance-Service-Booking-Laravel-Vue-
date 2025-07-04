<?php

use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Provider\AvailabilitySlotController;
use App\Http\Controllers\Provider\BookingController;
use App\Http\Controllers\Provider\ServiceController;
use App\Http\Controllers\ServiceAreaController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified', 'role:admin,provider'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->group(function () {
        Route::resource('service-categories', ServiceCategoryController::class);
        Route::resource('service-areas', ServiceAreaController::class);
    });

/*
|--------------------------------------------------------------------------
| Provider Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:admin,provider'])
    ->prefix('provider')
    ->group(function () {
        // Service Management
        Route::resource('services', ServiceController::class);
        Route::post('services/{service}/assign-area', [ServiceController::class, 'assignArea'])
            ->name('services.assign.area');
        Route::delete('services/{service}/assign-area/{area}', [ServiceController::class, 'removeArea'])
            ->name('services.remove.area');

        // Availability Slot Management
        Route::resource('availabilities', AvailabilitySlotController::class);

        // Booking Management
        Route::resource('bookings', BookingController::class);
    });

/*
|--------------------------------------------------------------------------
| User Profile Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth', 'verified')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Portal Routes
|--------------------------------------------------------------------------
*/
Route::prefix('portal')
    ->name('portal.')
    ->group(function () {
        Route::get('/', [PortalController::class, 'home'])->name('home');
        Route::get('/services', [PortalController::class, 'services'])->name('services');
        Route::get('/services/{service}', [PortalController::class, 'service'])->name('services.show');

        Route::middleware('auth', 'verified')->group(function () {
            Route::get('/bookings', [PortalController::class, 'bookings'])->name('bookings');
            Route::get('/completed', [PortalController::class, 'completed'])->name('completed');
            Route::get('/profile', [PortalController::class, 'profile'])->name('profile');
            Route::post('/booking/{service}', [PortalController::class, 'books'])->name('booking');
            Route::post('/cancel-booking/{booking}', [PortalController::class, 'booksCancel'])->name('booking.cancel');
        });
    });
/*
|--------------------------------------------------------------------------
| Portal Payment Routes
|--------------------------------------------------------------------------
*/
Route::prefix('portal/payments')
    ->name('portal.payments.')
    ->group(function () {
        Route::middleware('auth', 'verified')->group(function () {
            Route::post('/create/{booking}', [PaymentController::class, 'create'])->name('create');
            Route::get('/success/{booking}', [PaymentController::class, 'success'])->name('success');
            Route::get('/cancel/{booking}', [PaymentController::class, 'cancel'])->name('cancel');
        });
    });

// Route::get('/test-payment-email/{payment}', function (Payment $payment) {
//     dispatch(new \App\Jobs\SendPaymentConfirmation($payment));
//     return 'Job dispatched for payment ' . $payment->id;
// });

// Route::get('/debug-pdf/{payment}', function (App\Models\Payment $payment) {
//     $booking = $payment->booking;
//     $customer = $booking->customer;
//     $provider = $booking->provider;
//     return view('pdf.receipt', compact('payment', 'booking', 'customer', 'provider'));
// });




Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])->name('stripe.webhook');

// @formatter:off
require __DIR__.'/auth.php';
// @formatter:on
