<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('homepage');
});

Route::get('/admin/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');
Route::post('reset-password', [\App\Http\Controllers\Admin\Auth\ResetPasswordController::class, 'resetSubmission'])->middleware('guest')->name('password.update');

Route::get('/admin/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('admin.forgot-password');
Route::get('/admin/reset-password/{token}', function ($token) {
    return view('auth.admin-reset-password', ['token' => $token]);
})->middleware('guest')->name('admin.password.reset');
Route::post('/admin/send-token', [\App\Http\Controllers\Admin\Auth\ResetPasswordController::class, 'reset'])->middleware('guest')->name('admin.password.token');
Route::post('/admin/reset-password', [\App\Http\Controllers\Admin\Auth\ResetPasswordController::class, 'resetSubmission'])->middleware('guest')->name('admin.password.update');

Route::get('/admin/partner/reset-password/{token}', function ($token) {
    return view('auth.partner-reset-password', ['token' => $token]);
})->middleware('guest')->name('partner.password.reset');
Route::post('/partner/reset-password', [\App\Http\Controllers\Partner\PartnerController::class, 'resetSubmission'])->middleware('guest')->name('partner.password.update');

Route::get('/admin/{any}', function () {
    return view('dashboard');
})->where("any", ".*");
