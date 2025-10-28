<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PesananCustomerController;
use App\Http\Controllers\PesananAdminController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\DashController;
use App\Http\Controllers\UlasanController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index'])->name('halaman.landing-page');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'userMiddleware'])
    ->prefix('customer')
    ->group(function () {
        Route::get('pesanan/confirm', [PesananCustomerController::class, 'showConfirmationPage'])
            ->name('customer.pesanan.confirm');
        Route::get('pesanan/create', [PesananCustomerController::class, 'create'])
            ->name('customer.pesanan.create');
        Route::post('pesanan', [PesananCustomerController::class, 'store'])
            ->name('customer.pesanan.store');
        Route::get('pesanan/{id_transaksi}', [PesananCustomerController::class, 'show'])->name('customer.pesanan.show');
        Route::delete('pesanan/{id_transaksi}/batal', [PembayaranController::class, 'batalPesanan'])->name('customer.pesanan.batal');
        Route::get('pesanan', [PesananCustomerController::class, 'index'])->name('customer.pesanan.index');
        Route::get('pembayaran/{id_transaksi}/create', [PembayaranController::class, 'create'])->name('customer.pembayaran.create');
        Route::post('pembayaran/{id_transaksi}/store', [PembayaranController::class, 'store'])->name('customer.pembayaran.store');
        Route::post('pembayaran/store/{id_transaksi}', [PembayaranController::class, 'store'])->name('customer.pembayaran.store');
        Route::post('/ulasan', [UlasanController::class, 'store'])->name('ulasan.store');
        Route::delete('/ulasan/{id}', [UlasanController::class, 'destroy'])->name('ulasan.destroy');
        Route::put('/ulasan/{id}', [UlasanController::class, 'update'])->name('ulasan.update');
    });

Route::middleware(['auth', 'adminMiddleware'])
    ->prefix('admin')
    ->group(function () {
        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Pesanan
        Route::get('pesanan', [PesananAdminController::class, 'index'])->name('admin.pesanan.index');
        Route::get('pesanan/buatpesanan', [PesananAdminController::class, 'create'])
            ->name('admin.pesanan.buatpesanan');
        Route::post('pesanan', [PesananAdminController::class, 'store'])
            ->name('admin.pesanan.store');
        Route::get('pesanan/{id_transaksi}', [PesananAdminController::class, 'show'])->name('admin.pesanan.show');
        Route::get('pesanan/{id_transaksi}/edit-status', [PesananAdminController::class, 'editStatus'])->name('admin.pesanan.editStatus');
        Route::put('pesanan/{id_transaksi}/update-status', [PesananAdminController::class, 'updateStatus'])->name('admin.pesanan.updateStatus');
        Route::delete('pesanan/{id_transaksi}/batal', [PembayaranController::class, 'batalPesanan'])->name('admin.pesanan.batal');

        // Pembayaran
        Route::post('pembayaran/{id_transaksi}/bayar-cash', [PembayaranController::class, 'bayarCash'])->name('admin.pembayaran.bayarCash');
        // Jika Anda menggunakan route grup 'admin' di route::prefix atau route::name
        Route::post('pembayaran/{id_transaksi}/set-metode', [PembayaranController::class, 'setMetodeDanBayar'])
            ->name('admin.pembayaran.setMetodeDanBayar');
        Route::get('pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
        Route::get('pembayaran/{id_transaksi}/create', [PembayaranController::class, 'create'])->name('admin.pembayaran.create');
        Route::post('pembayaran/store/{id_transaksi}', [PembayaranController::class, 'store'])->name('admin.pembayaran.store');

        // Layanan
        Route::get('layanan', [LayananController::class, 'index'])->name('admin.layanan.index');
        Route::get('layanan/create', [LayananController::class, 'create'])->name('admin.layanan.create');
        Route::post('layanan/store', [LayananController::class, 'store'])->name('admin.layanan.store');

        // Soft delete
        Route::delete('layanan/{id_layanan}', [LayananController::class, 'destroy'])->name('admin.layanan.destroy');

        // Arsip layanan (soft deleted)
        Route::get('layanan/archive', [LayananController::class, 'archive'])->name('admin.layanan.archive');

        // Restore layanan dari arsip
        Route::post('layanan/restore/{id_layanan}', [LayananController::class, 'restore'])->name('admin.layanan.restore');
        Route::delete('ulasan/{id}', [UlasanController::class, 'destroyByAdmin'])->name('admin.ulasan.destroy');
    });

Route::middleware(['auth', 'ownerMiddleware'])
    ->prefix('owner')
    ->group(function () {
        Route::get('dashboard', [DashController::class, 'index'])->name('owner.dashboard');
        Route::get('laporan', [LaporanController::class, 'index'])->name('owner.laporan.laporan');
       });
       

require __DIR__ . '/auth.php';
