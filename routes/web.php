<?php

use App\Http\Controllers\PositionController;
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
    return view('index');
})->name('index');

Route::get('/position/list', [PositionController::class, 'list'])->name('position.list');
Route::get('/position/{geoHash?}', [PositionController::class, 'show'])->name('position.show');
Route::post('/position/store', [PositionController::class, 'store'])->name('position.store');
Route::delete('/position/delete/{geoHash?}', [PositionController::class, 'destroy'])->name('position.destroy');

