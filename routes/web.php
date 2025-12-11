<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Livewire\Home::class)->name('home');

Route::any('/admin/logout', function () {
    return redirect('/logout');
})->name('filament.admin.logout');
Route::any('/admin/login', function () {
    return redirect('/login');
})->name('filament.admin.login');

Route::group([
    'middleware' => [
        'auth',
        //'can:users:manage',
    ]
], function () {
    // Route::get('/', \App\Livewire\Stages::class)->name('home');
    Route::get('/assessments/{frameworkId?}', \App\Livewire\Frameworks::class)->name('frameworks');
    Route::get('/assessment-variants/{frameworkId?}/{assessmentId?}', \App\Livewire\Variants::class)->name('variants');

//    Route::get('/frameworks/{frameworkId?}/{stageId?}', \App\Livewire\Frameworks::class)->name('frameworks');
//    Route::get('/standards/{frameworkId?}/{stageId?}', \App\Livewire\Stages::class)->name('standards');
    Route::get('/summary/{frameworkId?}/{assessmentId?}', \App\Livewire\Summary::class)->name('summary');
    Route::get('/assessment/{assessmentId?}/{nodeId?}', \App\Livewire\Assessments::class)->name('questions');

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
