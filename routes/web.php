<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QcController;
use App\Http\Controllers\KioskController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\UserAddressController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminCatalogController;
use App\Http\Controllers\AdminReturnController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\CartController;

Route::get('/', function () {
    $featured = \App\Models\Costume::take(3)->get();
    return view('welcome', compact('featured'));
});

Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
    /** @var \App\Models\User $user */
    $user = $request->user();
    if ($user && $user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('catalog.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/katalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/katalog/{id}', [CatalogController::class, 'show'])->name('catalog.show');
Route::view('/tentang-kami', 'about')->name('about');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Pesanan (Riwayat)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/{id}/received', [OrderController::class, 'received'])->name('orders.received');
    Route::post('/orders/{id}/return', [OrderController::class, 'returnShipping'])->name('orders.returnShipping');
    Route::post('/orders/{id}/payment', [OrderController::class, 'updatePayment'])->name('orders.payment');
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    Route::middleware(['auth'])->group(function () {
        Route::get('/profile/address', [UserAddressController::class, 'index'])->name('address.index');
        Route::post('/profile/address', [UserAddressController::class, 'store'])->name('address.store');
        Route::get('/profile/address/search-village', [UserAddressController::class, 'searchVillage'])->name('address.search-village');
        Route::patch('/profile/address/{address}/primary', [UserAddressController::class, 'makePrimary'])->name('address.primary');
        Route::delete('/profile/address/{address}', [UserAddressController::class, 'destroy'])->name('address.destroy');
    });

    // Cart
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::middleware(['auth'])->group(function () {
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
        Route::get('/checkout/shipping-options', [CheckoutController::class, 'getShippingOptions'])->name('checkout.shipping-options');
    });

    // Admin Routes
    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/qc-barcode', [QcController::class, 'index'])->name('qc.index');
        Route::post('/qc-barcode/scan', [QcController::class, 'scan'])->name('qc.scan');

        Route::get('/rfid-kiosk', [KioskController::class, 'index'])->name('kiosk.index');
        Route::post('/rfid-kiosk/scan', [KioskController::class, 'scan'])->name('kiosk.scan');
        Route::post('/rfid-kiosk/register', [KioskController::class, 'register'])->name('kiosk.register');

        Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/history', [AdminBookingController::class, 'history'])->name('bookings.history');
        Route::post('/bookings/{booking}/confirm', [AdminBookingController::class, 'confirm'])->name('bookings.confirm');
        Route::post('/bookings/{booking}/reject', [AdminBookingController::class, 'reject'])->name('bookings.reject');
        Route::post('/bookings/{booking}/fee', [AdminBookingController::class, 'updateFee'])->name('bookings.fee');
        Route::post('/bookings/{booking}/ship', [AdminBookingController::class, 'ship'])->name('bookings.ship');

        Route::get('/laporan', [AdminReportController::class, 'index'])->name('report.index');
        Route::get('/laporan/export', [AdminReportController::class, 'export'])->name('report.export');

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('katalog', AdminCatalogController::class);
        Route::get('/return', [AdminReturnController::class, 'index'])->name('return.index');
        Route::post('/return/{bookingId}/complete', [AdminReturnController::class, 'complete'])->name('return.complete');

        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    });
});

require __DIR__.'/auth.php';
