<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LessonController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

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

Route::post('/register', [UserController::class, 'createUser']);
Route::post('/login', [UserController::class, 'userLogin']);


Route::middleware('auth:sanctum')->group(function () {
    Route::resource('/comment', CommentController::class);
    Route::resource('/lesson', LessonController::class);
    Route::get('/user/comments', [UserController::class, 'getUserComments']);
    Route::get('/user/lessons', [UserController::class, 'getUserwatchedLessons']);
});
