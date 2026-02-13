<?php

use Illuminate\Support\Facades\Route;

// Public Routes
Route::view('/', 'welcome');

// Protected Dashboard & Organization Routes
Route::middleware(['auth', 'verified'])->group(function () {

    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('team', 'team')->name('team');

    // Profile usually only needs 'auth', but keeping it here is fine
    Route::view('profile', 'profile')->name('profile');

    /* Future routes like 'payouts' or 'approvals'
       will go inside this group block.
    */

    // Secure the Team route
    Route::get('team', function () {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action. Only Admins can manage the team.');
        }
        return view('team');
    })->name('team');

    // Only Requesters (or admins for testing) should access this
    Route::view('payouts/create', 'livewire.payouts.create')
    ->middleware(['auth', 'verified'])
    ->name('payouts.create');
});

require __DIR__.'/auth.php';
