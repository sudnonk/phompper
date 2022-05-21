<?php

use App\Http\Controllers\PositionController;
use Illuminate\Support\Facades\Route;

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

Route::get('/position/list', [PositionController::class, 'list'])->name('position.list');
Route::post('/position/store', [PositionController::class, 'store'])->name('position.store');
Route::delete('/position/delete/{geoHash?}', [PositionController::class, 'destroy'])->name('position.destroy');
Route::get('/position/{geoHash?}', [PositionController::class, 'show'])->name('position.show');
