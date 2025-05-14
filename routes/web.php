<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;


Route::get('/', fn() => view('home'))
    ->name('home');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    Route::view('dashboard', 'admin.dashboard')
        ->name('dashboard');
    Route::view('students-data', 'admin.students-data')
        ->name('students-data');
});

require __DIR__.'/auth.php';

    Route::view('mahasiswa/dashboard', 'mahasiswa.dashboard')
        ->name('mahasiswa.dashboard');
    Route::view('mahasiswa/pengajuan-magang', 'mahasiswa.pengajuan_magang')
        ->name('mahasiswa.pengajuan-magang');
    Route::view('mahasiswa/pembaruan-status', 'mahasiswa.pembaruan_status')
        ->name('mahasiswa.pembaruan-status');
    Route::view('mahasiswa/log-mahasiswa', 'mahasiswa.log_mahasiswa')
        ->name('mahasiswa.log-mahasiswa');
    Route::view('mahasiswa/setting-profile', 'mahasiswa.setting_profile')
        ->name('mahasiswa.setting-profile');