<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

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

    public function index()
    {
        try {
            $model = new $this->modelClass;
            return response()->json($model::all(), 200);
        } catch (\Exception $e) {
            Log::error('Error en BaseCrudController@index:', ['exception' => $e]);
            return response()->json(['error' => 'Error al traer los datos del modelo: ' . $this->modelClass, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info('Entrando al método store en BaseCrudController');

            $validatedData = $request->validate($this->getStoreValidationRules());
            Log::info('Datos validados en BaseCrudController:', $validatedData);

            $model = new $this->modelClass;
            $createdModel = $model::create($validatedData);
            Log::info('Modelo creado en BaseCrudController:', $createdModel->toArray());

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
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            Log::error('Error en BaseCrudController@destroy - ModelNotFoundException:', ['exception' => $e]);
            return response()->json(['error' => 'Error, entrada no encontrada para eliminar en el modelo: ' . $this->modelClass, 'message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            Log::error('Error al eliminar los datos en BaseCrudController@destroy:', ['exception' => $e]);
            return response()->json(['error' => 'Error al eliminar los datos para el modelo: ' . $this->modelClass, 'message' => $e->getMessage()], 500);
        }
    }
}
