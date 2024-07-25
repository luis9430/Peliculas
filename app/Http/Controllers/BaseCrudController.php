<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

/** 
 *  Este controlador fue creado para reutilizarlo cuando sea necesario
 *  contiene los metodos basicos para consultar m creaar, actualizar, eliminar
 *  getStoreValidationRules es para agregar validacion y a la vez agregar los campos a guardar 
 *  getUpdateValidationRules es para agregar validacion y a la vez agregar los campos a actualizar
 *  la diferencia entre ambos es porque en update no siempre se guardan todos los campos y en create si deben guardarse
 *  se agregaron logs que se pueden consultar para visualizar problemas
 *  se debe pasar el modelo como string cuando se usa protected $modelClass; ejemplo:  protected $modelClass = 'App\Models\User';
 */ 

abstract class BaseCrudController extends Controller
{
    protected $modelClass;

    protected function getStoreValidationRules(): array
    {
        return [];
    }

    protected function getUpdateValidationRules(): array
    {
        return [];
    }

    public function index(Request $request)
    {
        try {
            $model = new $this->modelClass;


            $query = $model::query();


            /* 
            se agrega 'with' funciona para que  traiga las relaciones necesarias
            ejemplo 
            http://localhost:8000/api/movies?with=comments  | cada relacion separada por coma
            */
            $relations = [];

            if ($request->has('with')) {
                $relations = explode(',', $request->get('with'));
                Log::info('Cargando relaciones:', $relations);
                $query->with($relations);
            }

            /* 
            se agrega 'fields' funciona para que  traiga los campos necesarios en lugar de todos
            ejemplo 
            http://peliculas.test/api/movies?fields=title,release_date  | separados por coma
            con esto nos ahorramos el traer todos con all 
            */
            if ($request->has('fields')) {
                $fields = explode(',', $request->get('fields'));
                Log::info('Seleccionando campos:', $fields);
            }
            /*/ se agrega 'filter' funciona para agregar campos y utilizarlos como where 
            ejemplo 
            http://peliculas.test/api/movies?filter[title]=Inception 
            esto es igual a $movies = Movies::where('title' , 'Inception')
            ademas se agrega un foreach , ya que acepta N cantidad e filters         
            es totalmente opcional , se agrego un if para que no sea requerido
            */

            if ($request->has('filter')) {  
                $filters = $request->get('filter');
                foreach ($filters as $field => $value) {
                    $query->where($field, $value);
                }
            }

            /* NOTA  

            los  filter y with, pueden ir juntos ejemplo: 
            http://peliculas.test/api/List?with=comments&filter[id]=37

            pero si se pone fields no salen los comentarios ya que fields usa SELECT y con ello se pierde el llamado de with en automatico 
            http://peliculas.test/api/List?filter[id]=37&fields=title,review&with=comments


            */      

            
               // Si no se especifican campos ni filtros, se devolveran todos los registros
               if (!$request->has('fields') && !$request->has('filter') && !$request->has('with')) {
                Log::info('Recuperando todos los registros');
                return response()->json($model::all(), 200);
            }

            $result = $query->get();

            if (!empty($relations)) {
                $result->load($relations);
            }
      

            return response()->json($result, 200);  
        } catch (\Exception $e) {
            Log::error('Error en BaseCrudController@index:', ['exception' => $e]);
            return response()->json(['error' => 'Error al traer los datos del modelo: ' . $this->modelClass, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {

            $validatedData = $request->validate($this->getStoreValidationRules());

            $model = new $this->modelClass;
            $createdModel = $model::create($validatedData);

            return response()->json($createdModel, 201);
        } catch (ValidationException $e) {
            Log::error('Error de validación en BaseCrudController@store:', ['exception' => $e]);
            return response()->json(['error' => 'Error de validacion en el modelo: ' . $this->modelClass, 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error al crear el registro del modelo en BaseCrudController@store:', ['exception' => $e]);
            return response()->json(['error' => 'Error al crear el registro del modelo: ' . $this->modelClass, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $model = new $this->modelClass;
            return response()->json($model::findOrFail($id), 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Error en BaseCrudController@show - ModelNotFoundException:', ['exception' => $e]);
            return response()->json(['error' => 'Error, entrada no encontrada en el modelo: ' . $this->modelClass, 'message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            Log::error('Error en BaseCrudController@show:', ['exception' => $e]);
            return response()->json(['error' => 'Error al buscar entradas en el modelo: ' . $this->modelClass, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate($this->getUpdateValidationRules());
            $model = new $this->modelClass;
            $existingModel = $model::findOrFail($id);
            $existingModel->update($validatedData);
            return response()->json($existingModel, 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Error en BaseCrudController@update - ModelNotFoundException:', ['exception' => $e]);
            return response()->json(['error' => 'Error, entrada no encontrada para actualizar en el modelo: ' . $this->modelClass, 'message' => $e->getMessage()], 404);
        } catch (ValidationException $e) {
            Log::error('Error de validación en BaseCrudController@update:', ['exception' => $e]);
            return response()->json(['error' => 'Error de validacion en el modelo: ' . $this->modelClass, 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error al actualizar los datos en BaseCrudController@update:', ['exception' => $e]);
            return response()->json(['error' => 'Error al actualizar los datos para el modelo: ' . $this->modelClass, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $model = new $this->modelClass;
            $existingModel = $model::findOrFail($id);
            $existingModel->delete();
            return response()->json(['message' => 'Registro eliminado exitosamente.'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Error, entrada no encontrada para eliminar en el modelo: ' . $this->modelClass, 'message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            Log::error('Error al eliminar los datos en BaseCrudController@destroy:', ['exception' => $e]);
            return response()->json(['error' => 'Error al eliminar los datos para el modelo: ' . $this->modelClass, 'message' => $e->getMessage()], 500);
        }
    }
}
