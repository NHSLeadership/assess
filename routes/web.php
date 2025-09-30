<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::group([
    //'middleware' => ['auth']
], function () {
    Route::get('forms/{groupId?}', \App\Livewire\Competency::class)->name('forms');
});

require __DIR__.'/auth.php';
