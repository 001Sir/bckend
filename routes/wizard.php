<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Setup Wizard Routes
|--------------------------------------------------------------------------
|
| Setup wizard routes to help setup the environment on the installation
| environment.
|
*/

Route::get('/step-1', function () {
    return view('wizard.step-1');
})->name('wizard.step1');
Route::get('/step-2', function () {
    return view('wizard.step-2');
})->name('wizard.step2');
Route::get('/step-3', function () {
    return view('wizard.step-3');
})->name('wizard.step3');
Route::get('/step-4', function () {
    return view('wizard.step-4');
})->name('wizard.step4');


Route::post('/submit-step1', [\App\Http\Controllers\SetupWizardController::class, 'handleStepOne'])->name('wizard.step1.form');
Route::post('/submit-step2', [\App\Http\Controllers\SetupWizardController::class, 'handleStepTwo'])->name('wizard.step2.form');
Route::post('/submit-step3', [\App\Http\Controllers\SetupWizardController::class, 'handleStepThree'])->name('wizard.step3.form');
Route::post('/submit-step4', [\App\Http\Controllers\SetupWizardController::class, 'handleStepFour'])->name('wizard.step4.form');
