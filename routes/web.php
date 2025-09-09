<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuypacketController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PacketController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/packets/filter', [HomeController::class, 'filter'])->name('packets.filter');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'store'])->name('register.store');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/test', function () {
    return view('test');
});

// Discount tracking endpoint (accessible to all users)
Route::post('/track-discount-click', [DiscountController::class, 'trackClick'])->name('track-discount-click');

Route::middleware('role:admin')->prefix('admin')->group(function () {
    Route::get('/pembayaran', [AdminController::class, 'index'])->name('admin.pembayaran');
    Route::get('/pembayaran/data', [AdminController::class, 'pembayaranData'])->name('admin.pembayaran.data');
    Route::post('/pembayaran/verifikasi', [AdminController::class, 'verifyPayment'])->name('admin.pembayaran.verifikasi');
    Route::get('/data-siswa', [AdminController::class, 'dataSiswa'])->name('admin.data-siswa');
    Route::get('/data-siswa/data', [AdminController::class, 'dataSiswaData'])->name('admin.data-siswa.data');
    Route::get('/pengaturan', [AdminController::class, 'pengaturan'])->name('admin.pengaturan');
    Route::put('/pengaturan', [AdminController::class, 'updatePengaturan'])->name('admin.pengaturan.update');
    Route::get('/discounts', [DiscountController::class, 'index'])->name('admin.discounts');
});

Route::middleware('role:student')->group(function () {
    Route::get('/user-profile', [ProfileController::class, 'view_profile'])->name('user.profile');
    Route::post('/user-profile/update', [ProfileController::class, 'update_profile'])->name('user.profile.update');
    Route::get('/user-kelas', [ProfileController::class, 'view_kelas'])->name('user.kelas');
    Route::get('/beli-paket', [PacketController::class, 'beli_paket'])->name('beli-paket.index');
    Route::get('/beli-konfirmasi', [PacketController::class, 'beli_konfirmasi'])->name('beli-konfirmasi');
    Route::get('/beli-paket/{packet_id}', [BuypacketController::class, 'beliPaket'])->name('beli-paket.show');
    Route::post('/validate-discount', [BuypacketController::class, 'validateDiscount'])->name('validate-discount');
    Route::get('/get-available-discounts', [BuypacketController::class, 'getAvailableDiscounts'])->name('get-available-discounts');
    Route::post('/proses-pembayaran', [PaymentController::class, 'store'])->name('proses-pembayaran');
});
