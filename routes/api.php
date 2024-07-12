<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/signup' , [AuthController::class ,  'signup']);
Route::post('/signin' , [AuthController::class ,  'signin']);


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout' , [AuthController::class , 'logout']);

    Route::post('/todo/create' , [TodoController::class , 'create']);
    Route::get('/todos' , [TodoController::class , 'index']);
    Route::post('/todos/search' , [TodoController::class , 'search']);
    Route::post('/todos/delete' , [TodoController::class , 'destroy']);
    Route::get('/download/file/{file}' , [TodoController::class , 'downloadFile']);
});