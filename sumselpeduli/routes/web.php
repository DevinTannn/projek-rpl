<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CampaignController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/upgrade', [ProfileController::class, 'upgrade'])->name('profile.upgrade');
});

// Campaign Management (Fundraiser Only)
Route::middleware(['auth', 'role:fundraiser'])->group(function () {
    Route::get('/my-campaigns', [CampaignController::class, 'myIndex'])->name('campaigns.index');
    Route::post('/my-campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/my-campaigns/{id}', [CampaignController::class, 'show'])->name('campaigns.show');
    Route::patch('/my-campaigns/{id}/tag', [CampaignController::class, 'updateTag'])->name('campaigns.update-tag');
});

Route::get('/preview', function () {
    return view('demo_shell');
})->name('preview');
