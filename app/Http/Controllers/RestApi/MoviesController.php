<?php

namespace App\Http\Controllers\RestApi;

use App\Http\Controllers\BaseCrudController;

use Illuminate\Http\Request;

class MoviesController extends BaseCrudController
{
    protected $modelClass = 'App\Models\Movie';
    
      // no necesitamos agregar un metodo para traer todos los resultados de comments ya que la url  http://peliculas.test/api/movies obtenemos todos los comentarios
    // Tampoco necesitamos  sobreescribir el metodo destroy ya que solo pasamos el id y seleccionamos el metodo DELETE  ejemplo http://peliculas.test/api/movies/21

    protected function getStoreValidationRules(): array
    {
        return [
            'title' => 'required|string',
            'synopsis' => 'required|string',
            'poster' => 'required|string',
            'review' => 'required|string',
            'release_date' => 'required|string',
        ];
        // no es necesario que todos vayan required solo los puse para que se vea completa la informacion al guardar 
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
            'title' => 'string',
            'synopsis' => 'string',
            'poster' => 'string',
            'review' => 'string',
            'release_date' => 'string',
        
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
