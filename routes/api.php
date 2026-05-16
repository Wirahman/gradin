<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourierController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// ==========================================
// ROUTE API UNTUK CRUD KURIR (COURIERS)
// ==========================================

Route::get('/couriers', [CourierController::class, 'index']);
Route::get('/couriers/{uuid}', [CourierController::class, 'show']);
Route::post('/couriers', [CourierController::class, 'store']);
Route::put('/couriers/{uuid}', [CourierController::class, 'update']);
Route::delete('/couriers/{uuid}', [CourierController::class, 'destroy']);