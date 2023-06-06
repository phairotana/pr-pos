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
    return redirect('/admin');
})->name('login');

Route::group(['middleware' => ['web', config('backpack.base.middleware_key', 'admin')], 'prefix' => 'admin'], function () {
    Route::get('address', [\App\Http\Controllers\Api\AddressController::class, 'index'])->name('address.index');
});
