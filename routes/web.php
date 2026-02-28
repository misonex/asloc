<?php

use App\Livewire\Onboarding\Wizard;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::group(['middleware' => ['auth']], function() {
    //Route::resource('roles', RoleController::class);
    //Route::resource('users', UserController::class);
});

Route::get('/onboarding/{token}', Wizard::class)->name('onboarding.wizard');
    
require __DIR__.'/settings.php';
