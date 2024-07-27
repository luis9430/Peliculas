<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestApi\MoviesController;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');

Route::get('/pelicula/{id}', [App\Http\Controllers\HomeController::class, 'show'])->name('movies.show');
