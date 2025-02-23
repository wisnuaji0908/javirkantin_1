<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MidtransCallbackController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider within a group
| which is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route untuk menangani callback Midtrans tanpa kena CSRF
Route::post('/midtrans/callback', [MidtransCallbackController::class, 'handleMidtransCallback'])
    ->name('midtrans.callback');

// Route dummy untuk cek user (opsional)
Route::get('/user', function (Request $request) {
    return response()->json(['user' => $request->user()]);
})->middleware('auth:sanctum');

// Route fallback untuk menangani jika route tidak ditemukan
Route::fallback(function () {
    return response()->json(['error' => 'Route not found'], 404);
});
