<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => [
        'auth',
        //'can:users:manage',
    ]
], function () {
    Route::get('/', \App\Livewire\Stages::class)->name('home');
    Route::get('/frameworks/{frameworkId?}', \App\Livewire\Frameworks::class)->name('frameworks');
    Route::get('/stages/{stageId?}', \App\Livewire\Stages::class)->name('stages');
    Route::get('/areas/{frameworkId?}', \App\Livewire\Areas::class)->name('areas');
    Route::get('/assessments/{assessmentId?}', \App\Livewire\Assessments::class)->name('assessments');

    /**
     * Request review
     */
    Route::get('/request/{assessmentId}', \App\Livewire\ReviewRequest::class)->name('review-request');
});

Route::group([
    'middleware' => 'signed'
], function () {
    Route::get('/review/{hashId}', \App\Livewire\Review::class)->name('review');
});

require __DIR__.'/auth.php';
