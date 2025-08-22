<?php

use App\Http\Controllers\IpNumberController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/',[SiteController::class,'home'])->name('home');
Route::post('/ajax-login', [\App\Http\Controllers\Auth\AjaxAuthController::class, 'login'])->name('ajax.login');
Route::post('/ajax-register', [App\Http\Controllers\Auth\AjaxAuthController::class, 'register'])->name('ajax.register');
Route::post('/ajax-password-send-otp', [App\Http\Controllers\Auth\AjaxAuthController::class, 'resetPasswordSendOtp'])->name('ajax.password_send_otp');
Route::post('/ajax-password-verify-otp', [App\Http\Controllers\Auth\AjaxAuthController::class, 'resetPasswordVerifyOtp'])->name('ajax.password_verify_otp');
Route::post('/otp-send', [App\Http\Controllers\Auth\AjaxAuthController::class, 'sendOtp'])->name('ajax.otp_send');
Route::post('/otp-verify', [App\Http\Controllers\Auth\AjaxAuthController::class, 'verifyOtp'])->name('ajax.otp_verify');
Route::get('/otp-login',[\App\Http\Controllers\SiteController::class,'otp_login'])->name('otp_login')->middleware('guest');
Route::get('/login',[\App\Http\Controllers\SiteController::class,'login'])->name('login')->middleware('guest');
Route::get('/register',[\App\Http\Controllers\SiteController::class,'register'])->name('register')->middleware('guest');
Route::get('/password-reset',[\App\Http\Controllers\SiteController::class,'password_reset'])->name('password.reset')->middleware('guest');

Route::get('/search-number', [SiteController::class, 'searchNumber'])->name('search_number');
Route::get('/number-purchase/{id}', [SiteController::class, 'numberPurchase'])->name('number_purchase');
Route::post('/number-purchase-submit/{id}', [SiteController::class, 'numberPurchaseSubmit'])->name('number_purchase_submit');
Route::get('/apply-number', [SiteController::class, 'applyNumber'])->name('apply_number');
Route::post('/apply-number-submit', [SiteController::class, 'applyNumberSubmit'])->name('apply_number_submit');
Route::get('/packages', [SiteController::class, 'package'])->name('package');
Route::get('/recharge', [SiteController::class, 'recharge'])->name('recharge');
Route::post('/recharge-ip', [IpNumberController::class, 'recharge'])->name('recharge.ipnumber');
Route::get('/bill-pay', [SiteController::class, 'billPay'])->name('bill_pay');
Route::post('/bill-payment', [IpNumberController::class, 'bill_payment'])->name('bill_payment');
Route::get('/minute-bundle', [SiteController::class, 'minuteBundle'])->name('minute_bundle');
Route::get('/thank-you', [SiteController::class, 'thankYou'])->name('thank_you');

Route::get('order-details/{id}',[\App\Http\Controllers\OrderController::class,'order_details'])->name('order_details');
Route::post('order-pay/{id}',[\App\Http\Controllers\OrderController::class,'order_pay'])->name('order_pay');
Route::get('/manual-payment/{payment_id}',[\App\Http\Controllers\PaymentController::class,'manual_payment'])->name('manual_payment');
Route::post('/manual-payment/{payment_id}', [\App\Http\Controllers\PaymentController::class, 'manual_payment_submit'])->name('manual_payment.submit');
Route::post('/pay-station/callback/{id}', [\App\Http\Controllers\Payment\PayStationController::class, 'payStationCallback'])->name('pay_station.callback');

Route::get('/api/search-ip', [IpNumberController::class, 'searchIp']);
Route::post('/order-minute-bundle', [IpNumberController::class, 'orderMinuteBundle']);
Route::get('/{slug}', [SiteController::class, 'slug'])->name('slug');

require __DIR__.'/auth.php';
