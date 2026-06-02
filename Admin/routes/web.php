<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\VerificationController;

Route::prefix('admin')->group(function () {
    Route::get('/', function () { return view('admin.dashboard'); })->name('admin.dashboard');
    
    // Verifikasi Kampanye
    Route::get('/verifikasi/kampanye', [VerificationController::class, 'campaignIndex'])->name('admin.verify.campaign.index');
    Route::post('/verifikasi/kampanye/{id}', [VerificationController::class, 'verifyCampaign'])->name('admin.verify.campaign.update');
    
    // Verifikasi Akun/Fundraiser
    Route::get('/verifikasi/akun', [VerificationController::class, 'accountIndex'])->name('admin.verify.account.index');
    Route::post('/verifikasi/akun/{id}', [VerificationController::class, 'verifyAccount'])->name('admin.verify.account.update');

    // Verifikasi Donasi Manual
    Route::get('/verifikasi/donasi', [VerificationController::class, 'donationIndex'])->name('admin.verify.donation.index');
    Route::post('/verifikasi/donasi/{id}', [VerificationController::class, 'verifyDonation'])->name('admin.verify.donation.update');
    
    // Laporan
    Route::get('/laporan', function () { return view('admin.laporan'); })->name('admin.laporan');
});