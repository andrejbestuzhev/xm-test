<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/stocks', [\App\Http\Controllers\StocksController::class, 'index'])->name('All stocks as array');
Route::get('/stocks/{symbol}', [\App\Http\Controllers\StocksController::class, 'index'])->name('Show only one stock');
Route::get('/', [\App\Http\Controllers\IndexController::class, 'index'])->name('Web page');
