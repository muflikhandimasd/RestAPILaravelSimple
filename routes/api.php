<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
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

Route::group(['middleware' => 'auth:sanctum'], function () {
    // CRUD Product
    Route::get('/get-product', [ProductController::class, 'getQuery']);
    Route::get('/get-detail/{id}', [ProductController::class, 'getDetail']);
    Route::post('/create-product', [ProductController::class, 'create']);
    Route::put('/update-product/{id}', [ProductController::class, 'update']);
    Route::delete('/delete-product/{id}', [ProductController::class, 'delete']);

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Login Register Auth
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
