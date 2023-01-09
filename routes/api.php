<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\ColumnController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/list-cards', [CardController::class, 'index']);
});


Route::controller(ColumnController::class)->group(function () {
    Route::get('/columns', 'index');
    Route::post('/columns', 'store');
    Route::delete('/columns/{column}', 'destroy');
});


Route::controller(CardController::class)->group(function () {
    Route::put('/cards/{card}', 'update');
    Route::post('/columns/{column}/cards', 'store');
    Route::post('/columns/{column}/cards/moved', 'moved');
});


