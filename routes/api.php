<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestApi\RegisterController;
use App\Http\Controllers\RestApi\LoginController;
use App\Http\Controllers\RestApi\ListController;
use App\Http\Controllers\RestApi\DetailController;
use App\Http\Controllers\RestApi\CommentController;
use App\Http\Controllers\RestApi\MoviesController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [LoginController::class, 'login']); 
Route::post('register', [RegisterController::class, 'store']);


// proteger rutas

Route::get('List', [ListController::class, 'index']);
Route::get('Details', [DetailController::class, 'index']);
Route::resource('comments', CommentController::class);
Route::resource('movies', MoviesController::class);
