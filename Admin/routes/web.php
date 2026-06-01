<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::get('/', function () { return view('admin.dashboard'); });
    
    // Verifikasi
    Route::get('/verifikasi/kampanye', function () { return view('admin.verifikasi.kampanye'); });
    Route::get('/verifikasi/akun', function () { return view('admin.verifikasi.akun'); });
    
    // Laporan
    Route::get('/laporan', function () { return view('admin.laporan'); });
});