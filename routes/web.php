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


Route::get('/home', function () {
    return view('movies.index');
});
//->middleware('auth:sanctum');//no redirige si se agrega la proteccion con el middleware   (posible error en CORS) el token si se guarda y se utiliza  en /home para los permisos de la api







