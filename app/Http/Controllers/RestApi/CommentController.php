<?php

namespace App\Http\Controllers\RestApi;

use App\Http\Controllers\BaseCrudController;
use Illuminate\Http\Request;

class CommentController extends BaseCrudController
{
    protected $modelClass = 'App\Models\Comment';  
    
    // no necesitamos agregar un metodo para traer todos los resultados de comments ya que la url  http://peliculas.test/api/comments obtenemos todos los comentarios
    // Tampoco necesitamos  sobreescribir el metodo destroy ya que solo pasamos el id y seleccionamos el metodo DELETE  ejemplo http://peliculas.test/api/comments/6

    protected function getStoreValidationRules(): array
    {
        return [
            'movie_id' => 'required|integer',
            'user_id' => 'required|integer',
            'comment_text' => 'required|string',
        ];
    }

    public function store(Request $request) 
    {
        try {
            // Validamos los datos utilizando las reglas de validación definidas
            $validatedData = $request->validate($this->getStoreValidationRules());

            return parent::store(new Request($validatedData));       

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al procesar la solicitud', 'message' => $e->getMessage()], 500);
        }
    }


    protected function getUpdateValidationRules(): array
    {
        return [
            'comment_text' => 'required|string',
        ];
    }



    public function update(Request $request, $id) 
    {
        try {
            // Validar los datos utilizando las reglas de validación definidas
            $validatedData = $request->validate($this->getUpdateValidationRules());

            // Llamando al método update del controlador padre con los datos validados
            return parent::update(new Request($validatedData), $id);       

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al procesar la solicitud', 'message' => $e->getMessage()], 500);
        }
    }
 
}
