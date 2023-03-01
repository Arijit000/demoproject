<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ImageController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/image', [ImageController::class, 'store']);
    Route::get('/auth/fetch-image', [ImageController::class, 'index']);
    Route::get('/auth/single-image/{id}', [ImageController::class, 'show']);
    Route::put('/auth/update-image/{id}', [ImageController::class, 'update']);
    Route::delete('/auth/delete-image/{id}', [ImageController::class, 'delete']);
    Route::post('/auth/check', [ImageController::class, 'check']);
});
