<?php

namespace App\Http\Controllers\RestApi;

use App\Http\Controllers\BaseCrudController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

/*
En este controlador se realiza el registro de usuarios
utiliza el controlador  BaseCrudController , hereda sus metodos
se necesita llamar al modelo como string y agregar la ruta donde se encuentra

*/


class RegisterController extends BaseCrudController
{
    protected $modelClass = 'App\Models\User';

    protected function getStoreValidationRules(): array // agregando un array de los campos que necesitamos guardar se realiza una sobrecarga del metodo
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ];
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate($this->getStoreValidationRules());
            $validatedData['password'] = Hash::make($validatedData['password']);

            return parent::store(new Request($validatedData));
        } catch (ValidationException $e) {
            Log::error('Error de validación en RegisterController@store:', ['exception' => $e]);
            return response()->json(['error' => 'Error de validación', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error en RegisterController@store:', ['exception' => $e]);
            return response()->json(['error' => 'Error al procesar la solicitud', 'message' => $e->getMessage()], 500);
        }
    }

}
