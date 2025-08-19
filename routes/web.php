<?php

use App\Http\Controllers\IpNumberController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/',[SiteController::class,'home'])->name('home');
Route::get('/search-number', [SiteController::class, 'searchNumber'])->name('search_number');
Route::get('/apply-number', [SiteController::class, 'applyNumber'])->name('apply_number');
Route::post('/apply-number-submit', [SiteController::class, 'applyNumberSubmit'])->name('apply_number_submit');
Route::get('/packages', [SiteController::class, 'package'])->name('package');
Route::get('/recharge', [SiteController::class, 'recharge'])->name('recharge');
Route::post('/recharge-ip', [IpNumberController::class, 'recharge'])->name('recharge.ipnumber');
Route::get('/bill-pay', [SiteController::class, 'billPay'])->name('bill_pay');
Route::get('/minute-bundle', [SiteController::class, 'minuteBundle'])->name('minute_bundle');
Route::get('/thank-you', [SiteController::class, 'thankYou'])->name('thank_you');
Route::get('/{slug}', [SiteController::class, 'slug'])->name('slug');

require __DIR__.'/auth.php';
