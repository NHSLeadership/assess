<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::group([
    'middleware' => [
        'auth',
        //'can:users:manage',
    ]
], function () {
    // Route::get('competencies', \App\Livewire\Competency::class)->name('competencies');
});

require __DIR__.'/auth.php';
