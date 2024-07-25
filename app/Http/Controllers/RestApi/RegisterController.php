<?php

namespace App\Http\Controllers\RestApi;

use App\Http\Controllers\BaseCrudController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\ArrayShape;

class RegisterController extends BaseCrudController
{
    protected $modelClass = 'App\Models\User';

    #[ArrayShape(['name' => "string", 'email' => "string", 'password' => "string"])]
    protected function getStoreValidationRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ];
    }

    public function store(Request $request)
    {
        Log::info('Entrando al método store en RegisterController');

        try {
            $validatedData = $request->validate($this->getStoreValidationRules());
            Log::info('Datos validados:', $validatedData);

            $validatedData['password'] = Hash::make($validatedData['password']);
            Log::info('Password hasheada');

            return parent::store(new Request($validatedData));
        } catch (\Exception $e) {
            Log::error('Error en RegisterController@store:', ['exception' => $e]);
            return response()->json(['error' => 'Error al procesar la solicitud'], 500);
        }
    }
}
