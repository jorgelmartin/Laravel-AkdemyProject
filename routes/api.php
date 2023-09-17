<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConvocationController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InscriptionController;
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
Route::post('/convocation/create', [ConvocationController::class, 'createConvocations'])->middleware(['auth:sanctum', 'isAdmin']);
Route::put('/convocation/update/{id}', [ConvocationController::class, 'updateConvocations'])->middleware(['auth:sanctum', 'isAdmin']);

//USER CONTROLLER
Route::delete('/user/delete', [UserController::class, 'deleteMyAccount'])->middleware('auth:sanctum');
Route::post('/user/{id}', [UserController::class, 'restoreAccount']);
Route::put('/user/update', [UserController::class, 'updateProfile'])->middleware('auth:sanctum');
Route::get('/user/getAll', [UserController::class, 'getAllUsers'])->middleware(['auth:sanctum', 'isAdmin']);

//USER CONVOCATION CONTROLLER
Route::post('/userConvo/create', [inscriptionController::class, 'createUserConvocations'])->middleware('auth:sanctum');
Route::get('/userConvo/getPending', [inscriptionController::class, 'getPendingUserRequests'])->middleware(['auth:sanctum', 'isAdmin']);
Route::post('/userConvo/accept/{id}', [inscriptionController::class, 'acceptUserRequest'])->middleware(['auth:sanctum', 'isAdmin']);
Route::get('/userConvo/getAccepted/{userId}', [inscriptionController::class, 'getMyAcceptedUserRequests'])->middleware(['auth:sanctum']);
Route::get('/userConvo/getAllInscriptions', [inscriptionController::class, 'getAllInscriptions'])->middleware(['auth:sanctum']);
Route::post('/userConvo/addComment/{userConvocationId}', [inscriptionController::class, 'addComment']);
// Route::post('/convocation/join', [ConvocationController::class, 'joinConvocation']);

//PROGRAMS CONTROLLER
Route::get('/program/getAll', [ProgramController::class, 'getAllPrograms']);