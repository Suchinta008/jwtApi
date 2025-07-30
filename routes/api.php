<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CRUD_Controller;

Route::get('/', function () {
    return response()->json(['message' => 'Hello world!']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('jwt')->group(function () {
    Route::post('/refreshToken', [AuthController::class, 'refreshToken']);
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::put('/user', [AuthController::class, 'updateUser']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/list-data', [CRUD_Controller::class, 'index']);
    Route::post('/submit-data', [CRUD_Controller::class, 'store']);
    Route::get('/show-data/{id}', [CRUD_Controller::class, 'show']);
    Route::post('/update-data/{id}', [CRUD_Controller::class, 'update']);
    Route::delete('/delete-data/{id}', [CRUD_Controller::class, 'destroy']);
});
