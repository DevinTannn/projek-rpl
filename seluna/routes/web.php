<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\FundraiserVerificationController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

Route::get('/', HomeController::class)->name('home');

Route::get('/search', [CampaignController::class, 'search'])->name('campaigns.search');
Route::get('/api/search', [CampaignController::class, 'apiSearch'])->name('api.campaigns.search');
Route::get('/api/sync-status', [CampaignController::class, 'syncStatus'])->name('api.sync');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Forgot Password / OTP Flow
    Route::get('/forgot-password',       [\App\Http\Controllers\ForgotPasswordController::class, 'showEmailForm'])->name('password.request');
    Route::post('/forgot-password',      [\App\Http\Controllers\ForgotPasswordController::class, 'sendOtp'])->name('password.send-otp');
    Route::get('/verify-otp',            [\App\Http\Controllers\ForgotPasswordController::class, 'showOtpForm'])->name('password.verify-otp');
    Route::post('/verify-otp',           [\App\Http\Controllers\ForgotPasswordController::class, 'verifyOtp'])->name('password.do-verify-otp');
    Route::get('/reset-password',        [\App\Http\Controllers\ForgotPasswordController::class, 'showResetForm'])->name('password.reset-form');
    Route::post('/reset-password',       [\App\Http\Controllers\ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/archived', [DonationController::class, 'archive'])->name('profile.archived');
    Route::get('/profile/verification', [FundraiserVerificationController::class, 'show'])->name('profile.verification');
    Route::post('/profile/verification', [FundraiserVerificationController::class, 'store'])->name('profile.verification.store');
    Route::post('/profile/verification/org', [FundraiserVerificationController::class, 'updateOrganization'])->name('profile.verification.updateOrg');
    Route::get('/followed', [CampaignController::class, 'followedIndex'])->name('campaigns.followed');
    Route::get('/last-accessed', [CampaignController::class, 'lastAccessedIndex'])->name('campaigns.last-accessed');
    Route::get('/donations/{id}/certificate', [DonationController::class, 'downloadCertificate'])->name('donations.certificate');
    
    // Donation Route
    Route::post('/campaigns/{id}/donate', [DonationController::class, 'store'])->name('campaigns.donate');
    Route::post('/campaigns/{id}/follow', [CampaignController::class, 'toggleFollow'])->name('campaigns.follow');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/donations', [\App\Http\Controllers\Admin\AdminDonationController::class, 'index'])->name('donations.index');
    Route::post('/donations/{id}/verify', [\App\Http\Controllers\Admin\AdminDonationController::class, 'verify'])->name('donations.verify');
    Route::post('/donations/{id}/reject', [\App\Http\Controllers\Admin\AdminDonationController::class, 'reject'])->name('donations.reject');
    
    Route::get('/fundraisers', [\App\Http\Controllers\Admin\AdminFundraiserController::class, 'index'])->name('fundraisers.index');
    Route::post('/fundraisers/{id}/verify', [\App\Http\Controllers\Admin\AdminFundraiserController::class, 'verify'])->name('fundraisers.verify');
    Route::post('/fundraisers/{id}/reject', [\App\Http\Controllers\Admin\AdminFundraiserController::class, 'reject'])->name('fundraisers.reject');
});

Route::middleware('auth')->group(function () {
    Route::get('/my-campaigns', [CampaignController::class, 'myIndex'])->name('campaigns.index');
    Route::post('/my-campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/my-campaigns/{id}', [CampaignController::class, 'show'])->name('campaigns.show');
    
    // Restricted Management Actions
    Route::middleware('role:fundraiser')->group(function () {
        Route::put('/my-campaigns/{id}', [CampaignController::class, 'update'])->name('campaigns.update');
        Route::patch('/my-campaigns/{id}/tag', [CampaignController::class, 'updateTag'])->name('campaigns.update-tag');
        Route::delete('/my-campaigns/{id}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');
        Route::post('/my-campaigns/{id}/media', [CampaignController::class, 'uploadMedia'])->name('campaigns.media.upload');
        Route::delete('/my-campaigns/{campaignId}/media/{mediaId}', [CampaignController::class, 'deleteMedia'])->name('campaigns.media.delete');
        Route::post('/my-campaigns/{id}/updates', [CampaignController::class, 'addUpdate'])->name('campaigns.updates.store');
        Route::post('/my-campaigns/{campaignId}/updates/{updateId}', [CampaignController::class, 'updateUpdate'])->name('campaigns.updates.update');
        Route::delete('/my-campaigns/{campaignId}/updates/{updateId}', [CampaignController::class, 'deleteUpdate'])->name('campaigns.updates.delete');
        
        // Report Routes
        Route::post('/my-campaigns/{campaign}/reports', [\App\Http\Controllers\CampaignReportController::class, 'store'])->name('campaigns.reports.store');
        Route::delete('/reports/{report}', [\App\Http\Controllers\CampaignReportController::class, 'destroy'])->name('campaigns.reports.delete');
    });
});

// Midtrans Notification
Route::post('/midtrans/notification', [DonationController::class, 'notification'])->name('midtrans.notification');

Route::get('/events/{type}', [\App\Http\Controllers\EventController::class, 'show'])->name('events.show');

Route::get('/preview', function () {
    return view('demo_shell');
})->name('preview');
