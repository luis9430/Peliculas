<?php

namespace App\Http\Controllers\RestApi;
use App\Http\Controllers\BaseCrudController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * En este controlador trae los detalles  de las peliculas
 * no es necesario agregar nada mas ya que lo hereda de BaseCrudController
 * lo unico es llamar a index en la ruta 
 *  Route::get('Details', [DetailController::class, 'index']);
 * el index se hereda y al no necesitar modificarlo no es necesario sobreescribirlo 
 */

class DetailController extends BaseCrudController
{
    protected $modelClass = 'App\Models\Movie';


}
