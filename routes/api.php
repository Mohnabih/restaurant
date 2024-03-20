<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Menu\CategoryController;
use App\Http\Controllers\Api\Menu\DiscountController;
use App\Http\Controllers\Api\Menu\ItemController;
use App\Http\Controllers\Api\Menu\MenuController;
use App\Http\Controllers\Api\User\UserController;
use App\Models\Menu\Discount;
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

Route::post('register', [UserController::class, 'store']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::group(['prefix' => 'users', 'middleware' => 'auth:sanctum'], function () {
    Route::delete('delete/{id}', [UserController::class, 'destroy']);
});

Route::prefix('menus')->middleware('auth:sanctum')->group(function () {
    Route::post('create', [MenuController::class, 'store']);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('items', ItemController::class);
    Route::apiResource('discounts', DiscountController::class);
});
