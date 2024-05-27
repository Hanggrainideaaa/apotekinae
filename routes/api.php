<?php

use App\Http\Controllers\ApotekController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::get('/verify/{token}', [UsersController::class, 'verification'])->name('verify');
Route::post('/register', [UsersController::class, 'create']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/members', [AuthController::class, 'loginMembers']);
Route::post('/register/mitra', [UsersController::class, 'createMitra']);

Route::get('/apotek', [ApotekController::class, 'index']);
Route::post('/apotek', [ApotekController::class, 'create']);
Route::get('/apotek/{id}', [ApotekController::class, 'show']);
Route::put('/apotek/{id}', [ApotekController::class, 'update']);
Route::delete('/apotek/{id}', [ApotekController::class, 'destroy']);
Route::put('/apotek/verified/{id}', [ApotekController::class, 'isVerified']);

Route::post('/resep', [RequestController::class, 'create']);
Route::post('/transaction', [ResultController::class, 'create']);
Route::put('/accepted', [ResultController::class, 'isAccepted']);
Route::put('/taken', [ResultController::class, 'isTaken']);

Route::resource('/user', UsersController::class);
Route::put('/user/{id}', [UsersController::class, 'update']);
Route::delete('/user/{id}', [UsersController::class, 'destroy']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
