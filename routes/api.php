<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::get('users', [UserController::class, 'users']);
Route::get('users/search', [UserController::class, 'search']);
Route::get('users/suggested', [UserController::class, 'suggestedAccounts']);
Route::post('users', [UserController::class, 'add']);
Route::put('users', [UserController::class, 'update']);

