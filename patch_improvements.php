<?php

// 1. AdminBookingController - add reject
$adminController = file_get_contents('app/Http/Controllers/AdminBookingController.php');
if (strpos($adminController, 'function reject') === false) {
    $adminController = str_replace('use App\Mail\OrderShipped;', "use App\Mail\OrderShipped;\nuse App\Mail\PaymentRejected;", $adminController);
    $rejectMethod = <<<EOT

    public function reject(Request \$request, Booking \$booking)
    {
        if (\$booking->status !== 'Menunggu Konfirmasi') {
            return back()->with('error', 'Gagal: Hanya pesanan Menunggu Konfirmasi yang bisa ditolak.');
        }

        \$booking->update(['status' => 'Pembayaran Ditolak']);

        try {
            Mail::to(\$booking->user->email)->send(new PaymentRejected(\$booking));
        } catch (\Exception \$e) {
            Log::error('Failed to send PaymentRejected email: ' . \$e->getMessage());
        }

        return back()->with('success', 'Berhasil: Pembayaran ditolak dan email pemberitahuan telah dikirim ke pelanggan.');
    }
}
EOT;
    $adminController = preg_replace('/}\s*$/', $rejectMethod, $adminController);
    file_put_contents('app/Http/Controllers/AdminBookingController.php', $adminController);
}

// 2. OrderController - add updatePayment and cancel
$orderController = file_get_contents('app/Http/Controllers/OrderController.php');
if (strpos($orderController, 'function updatePayment') === false) {
    $methods = <<<EOT

    public function updatePayment(Request \$request, \$id)
    {
        \$request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        \$booking = Booking::where('user_id', Auth::id())->findOrFail(\$id);

        if (!in_array(\$booking->status, ['Menunggu Pembayaran', 'Pembayaran Ditolak'])) {
            return back()->with('error', 'Gagal: Tidak dapat mengunggah bukti bayar pada status ini.');
        }

        \$paymentPath = \$request->file('payment_proof')->store('payments', 'public');
        
        \$booking->update([
            'payment_proof' => \$paymentPath,
            'status' => 'Menunggu Konfirmasi',
        ]);

        return back()->with('success', 'Berhasil: Bukti pembayaran telah diunggah. Menunggu konfirmasi admin.');
    }

    public function cancel(\$id)
    {
        \$booking = Booking::where('user_id', Auth::id())->findOrFail(\$id);

        if (!in_array(\$booking->status, ['Menunggu Pembayaran', 'Menunggu Hitung Ongkir'])) {
            return back()->with('error', 'Gagal: Pesanan tidak dapat dibatalkan.');
        }

        \$booking->update(['status' => 'Dibatalkan']);
        return back()->with('success', 'Berhasil: Pesanan telah dibatalkan.');
    }
}
EOT;
    $orderController = preg_replace('/}\s*$/', $methods, $orderController);
    file_put_contents('app/Http/Controllers/OrderController.php', $orderController);
}

// 3. Routes
$routes = file_get_contents('routes/web.php');
if (strpos($routes, "Route::post('/admin/bookings/{booking}/reject'") === false) {
    $routes = str_replace(
        "Route::post('/admin/bookings/{booking}/confirm', [AdminBookingController::class, 'confirm'])->name('admin.bookings.confirm');",
        "Route::post('/admin/bookings/{booking}/confirm', [AdminBookingController::class, 'confirm'])->name('admin.bookings.confirm');\n        Route::post('/admin/bookings/{booking}/reject', [AdminBookingController::class, 'reject'])->name('admin.bookings.reject');",
        $routes
    );
    
    $routes = str_replace(
        "Route::post('/orders/{id}/return', [OrderController::class, 'returnShipping'])->name('orders.returnShipping');",
        "Route::post('/orders/{id}/return', [OrderController::class, 'returnShipping'])->name('orders.returnShipping');\n    Route::post('/orders/{id}/payment', [OrderController::class, 'updatePayment'])->name('orders.payment');\n    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');",
        $routes
    );
    file_put_contents('routes/web.php', $routes);
}

// 4. Admin Booking Index View
$adminView = file_get_contents('resources/views/admin/bookings/index.blade.php');
if (strpos($adminView, "admin.bookings.reject") === false) {
    $rejectBtn = <<<EOT
<form action="{{ route('admin.bookings.confirm', \$booking->id) }}" method="POST" class="inline-block" x-data @submit.prevent="\$dispatch('open-confirm', { title: 'Konfirmasi Pesanan', message: 'Anda yakin ingin mengonfirmasi pesanan ini?', form: \$el })">
                                        @csrf
                                        <button type="submit" class="inline-block bg-light-primary text-gray-900 rounded-sm px-4 py-2 font-semibold hover:bg-[#E5A5B0] transition-colors shadow-sm">Konfirmasi</button>
                                    </form>
                                    <form action="{{ route('admin.bookings.reject', \$booking->id) }}" method="POST" class="inline-block" x-data @submit.prevent="\$dispatch('open-confirm', { title: 'Tolak Pembayaran', message: 'Anda yakin ingin menolak bukti bayar ini?', form: \$el })">
                                        @csrf
                                        <button type="submit" class="inline-block bg-red-100 text-red-700 rounded-sm px-4 py-2 font-semibold hover:bg-red-200 border border-red-200 transition-colors shadow-sm">Tolak</button>
                                    </form>
EOT;
    $adminView = preg_replace('/<form action="{{ route\(\'admin\.bookings\.confirm\', \$booking->id\) }}".*?<\/form>/s', $rejectBtn, $adminView);
    file_put_contents('resources/views/admin/bookings/index.blade.php', $adminView);
}

// 5. Order Card View
$cardView = file_get_contents('resources/views/components/order-card.blade.php');
if (strpos($cardView, "orders.payment") === false) {
    $actions = <<<EOT
@if(in_array(\$booking->status, ['Menunggu Pembayaran', 'Pembayaran Ditolak']))
                <div class="mb-6 p-4 border border-red-100 bg-red-50 rounded-md">
                    @if(\$booking->status === 'Pembayaran Ditolak')
                        <p class="text-sm font-bold text-red-700 mb-2">⚠️ Bukti pembayaran sebelumnya ditolak.</p>
                    @endif
                    <form action="{{ route('orders.payment', \$booking->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
                        @csrf
                        <input type="file" name="payment_proof" accept="image/*" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-sm file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <button type="submit" class="shrink-0 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-sm shadow-sm transition-colors text-sm">Unggah Pembayaran</button>
                    </form>
                </div>
            @endif

            @if(in_array(\$booking->status, ['Menunggu Pembayaran', 'Menunggu Hitung Ongkir']))
                <form action="{{ route('orders.cancel', \$booking->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?');" class="inline-block mt-2">
                    @csrf
                    <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-800 underline">Batalkan Pesanan</button>
                </form>
            @endif
EOT;
    
    // Inject at the end of the lg:col-span-8 div
    $cardView = preg_replace('/(<\/div>\s*<\/div>\s*<!-- Sidebar Aksi -->)/s', "\n" . $actions . "\n$1", $cardView);
    file_put_contents('resources/views/components/order-card.blade.php', $cardView);
}

echo "Improvements patched!";
