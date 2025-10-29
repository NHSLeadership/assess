<?php

use Illuminate\Support\Facades\Route;

Route::group([
    //'middleware' => ['auth']
], function () {
    //Route::get('stage/{stageId?}', \App\Livewire\Assessments::class)->name('stage');
    Route::get('/{stageId?}', \App\Livewire\Assessments::class)->name('stages');
    Route::get('/assessments/{assessmentId?}', \App\Livewire\FormsView::class)->name('assessments');
    Route::get('/form/{formId?}', \App\Livewire\FormView::class)->name('form');
});

require __DIR__.'/auth.php';
