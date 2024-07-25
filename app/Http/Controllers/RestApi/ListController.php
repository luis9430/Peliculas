<?php

namespace App\Http\Controllers\RestApi;

use App\Http\Controllers\BaseCrudController;

/**
 * En este controlador trae la lista de peliculas
 * no es necesario agregar nada mas ya que lo hereda de BaseCrudController
 * lo unico es llamar a index en la ruta 
 * Route::get('movies', [ListController::class, 'index']);
 * el index se hereda y al no necesitar modificarlo no es necesario sobreescribirlo 
 */

class ListController extends BaseCrudController 
{
    protected $modelClass = 'App\Models\Movie';
}



