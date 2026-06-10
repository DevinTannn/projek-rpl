<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\VerificationController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Middleware\AdminAuthMiddleware;

Route::prefix('admin')->group(function () {
    // Unprotected Auth Routes
    Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
    Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout.get'); // support GET logout

    // Protected Admin Routes
    Route::middleware([AdminAuthMiddleware::class])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
        
        // Verifikasi Kampanye
        Route::get('/verifikasi/kampanye', [VerificationController::class, 'campaignIndex'])->name('admin.verify.campaign.index');
        Route::post('/verifikasi/kampanye/{id}', [VerificationController::class, 'verifyCampaign'])->name('admin.verify.campaign.update');
        
        // Verifikasi Akun/Fundraiser
        Route::get('/verifikasi/akun', [VerificationController::class, 'accountIndex'])->name('admin.verify.account.index');
        Route::post('/verifikasi/akun/{id}', [VerificationController::class, 'verifyAccount'])->name('admin.verify.account.update');

        // Verifikasi Donasi Manual
        Route::get('/verifikasi/donasi', [VerificationController::class, 'donationIndex'])->name('admin.verify.donation.index');
        Route::post('/verifikasi/donasi/{id}', [VerificationController::class, 'verifyDonation'])->name('admin.verify.donation.update');
        
        Route::get('/api/sync', [DashboardController::class, 'sync'])->name('admin.api.sync');
        
        // Verifikasi Laporan Kampanye
        Route::get('/verifikasi/laporan', [\App\Http\Controllers\AdminReportController::class, 'index'])->name('admin.verify.report.index');
        Route::post('/verifikasi/laporan/{report}/verify', [\App\Http\Controllers\AdminReportController::class, 'verify'])->name('admin.verify.report.verify');
        Route::post('/verifikasi/laporan/{report}/reject', [\App\Http\Controllers\AdminReportController::class, 'reject'])->name('admin.verify.report.reject');
        
        // Laporan
        Route::get('/laporan', function () { return view('admin.laporan'); })->name('admin.laporan');
    });
});
