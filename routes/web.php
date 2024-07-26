<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestApi\MoviesController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('login', function () {
    return view('auth.login');
})->name('login');


Route::get('register', function () {
    return view('auth.register');
})->name('register');


Route::resource('movies', MoviesController::class);








