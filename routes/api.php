<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\MessageCapsuleController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [UserAuthController::class, 'login']);
Route::post('register', [UserAuthController::class, 'register']);
Route::middleware('auth:sanctum')->post('logout', [UserAuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('message-capsules', [MessageCapsuleController::class, 'index']);
    Route::post('message-capsules', [MessageCapsuleController::class, 'store']);
    Route::get('message-capsules/{id}', [MessageCapsuleController::class, 'show']);
    Route::put('message-capsules/{id}', [MessageCapsuleController::class, 'update']);
    Route::delete('message-capsules/{id}', [MessageCapsuleController::class, 'destroy']);
});


