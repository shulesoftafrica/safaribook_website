<?php

use App\Http\Controllers\JoinController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('home');

Route::get('/join', [JoinController::class, 'form'])->name('join');
Route::post('/join', [JoinController::class, 'submit'])->middleware('throttle:10,1')->name('join.submit');

Route::get('/verify', [JoinController::class, 'otpForm'])->name('verify');
Route::post('/verify', [JoinController::class, 'verify'])->middleware('throttle:10,1')->name('verify.submit');
Route::post('/verify/resend', [JoinController::class, 'resend'])->middleware('throttle:5,1')->name('verify.resend');

Route::get('/up', fn () => response('ok'));
