<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OTPController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\OfferController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\V1\AddressController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\SettingController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\FavouriteController;
use App\Http\Controllers\Api\V1\DeviceTokenController;
use App\Http\Controllers\Api\Auth\MyNotificationAPIController;

Route::post('otp', [OTPController::class, 'index']);
Route::post('verify-otp', [OTPController::class, 'verify']);
Route::post('login', [AuthController::class, 'login'])->name('login.api');
Route::post('register', [AuthController::class, 'register'])->name('register.api');
Route::middleware(['auth:api'])->group(function () {
    Route::get('user', [AuthController::class, 'user'])->name('user.api');
    Route::put('user', [AuthController::class, 'update'])->name('user.update.api');
    Route::post('user/change-password', [AuthController::class, 'changePassword'])->name('user.change.password.api');
    Route::post('user/profile', [AuthController::class, 'uploadProfile'])->name('user.upload.profile.api');
    Route::post('user/logout', [AuthController::class, 'logout'])->name('user.logout.api');
    Route::put('user/notification/readordelete', [MyNotificationAPIController::class, 'readOrUnreadOrDelete']);
    Route::apiResource('user/notification', MyNotificationAPIController::class)->only(['index', 'update', 'show']);
});
Route::group(['middleware' => 'auth:api','prefix'=>'v1','as'=>'v1.'], function(){
    Route::delete('cart/{id}', [CartController::class, 'delete']);
    Route::delete('favourite/{id}', [FavouriteController::class, 'delete']);
    Route::apiResource('cart', CartController::class)->only(['index','store','update', 'destoy']);
    Route::apiResource('favourite', FavouriteController::class)->only(['index','store']);
    Route::apiResource('order', OrderController::class)->only(['index','store','show']);
    Route::apiResource('address', AddressController::class);
});
Route::group(['prefix'=>'v1','as'=>'v1.'], function(){
    Route::get('setting', [SettingController::class, 'index'])->name('setting.api');
    Route::get('category', [CategoryController::class, 'index'])->name('category');
    Route::get('product', [ProductController::class, 'index'])->name('product');
    Route::get('offer', [OfferController::class, 'index']);
    Route::get('product/{id}', [ProductController::class, 'show'])->name('show');
    Route::apiResource('device-token', DeviceTokenController::class)->only(['store']);
});

Route::get('/category', 'App\Http\Controllers\Api\SelectMultiApiController@indexCategories');
Route::get('/product/{id}', 'App\Http\Controllers\Api\SelectMultiApiController@showProduct');
Route::get('/category/{id}', 'App\Http\Controllers\Api\SelectMultiApiController.phpApi\SelectMultiApiController@showCategory');
Route::get('/branch', 'App\Http\Controllers\Api\Select2Controller@indexBranch');
Route::get('/customer', 'App\Http\Controllers\Api\Select2Controller@indexCustomer');
Route::get('invoice-detail', 'App\Http\Controllers\Api\InvoiceApiController@getInvoiceDetail');
