<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConvocationController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\UserController;
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
//AUTH CONTROLLER
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/profile', [AuthController::class, 'profile'])->middleware('auth:sanctum');



//CONVOCATION CONTROLLER
Route::get('/convocation/getAll', [ConvocationController::class, 'getAllConvocations']);
Route::post('/convocation/create', [ConvocationController::class, 'createConvocations']);
Route::put('/convocation/update', [ConvocationController::class, 'updateConvocations'])->middleware(['auth:sanctum', 'isAdmin']);

//USER CONTROLLER
Route::delete('/user/delete', [UserController::class, 'deleteMyAccount'])->middleware('auth:sanctum');
Route::post('/user/{id}', [UserController::class, 'restoreAccount']);
Route::put('/user/update', [UserController::class, 'updateProfile'])->middleware('auth:sanctum');
Route::get('/user/getAll', [UserController::class, 'getAllUsers'])->middleware(['auth:sanctum', 'isAdmin']);

//PROGRAMS CONTROLLER
Route::get('/program/getAll', [ProgramController::class, 'getAllPrograms']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
