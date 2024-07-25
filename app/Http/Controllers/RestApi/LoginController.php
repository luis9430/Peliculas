<?php

namespace App\Http\Controllers\RestApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/* 
Este es el controlador para iniciar sesion, en caso de que el correo y la contraseña sean correctos
arrojara un token , el nombre y el email

En caso contrario aparecera un mensaje idicando Error de validacion

Se agregaron logs para visualizar algun error extra en el controlador

*/ 

class LoginController extends Controller
{
    public function login(Request $request)
    {

        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:8',
            ]);

            $user = User::where('email', $request->email)->first(); // obtenemos el email y lo comparamos

            if (!$user || !Hash::check($request->password, $user->password)) {
                Log::warning('Credenciales inválidas');
                throw ValidationException::withMessages([
                    'email' => ['Las credenciales ingresadas son incorrectas.'],  // mensaje que aparece si el email o la contraseña no son correctos
                ]);
            }

            $token = $user->createToken('auth_token')->plainTextToken;  //creacion del token al iniciar sesion

            return response()->json(['token' => $token, 'nombre' => $user->name, 'email' => $user->email ]);
        } catch (ValidationException $e) {
            Log::error('Error de validacion en LoginController@login:', ['exception' => $e, 'errors' => $e->errors()]);
            return response()->json(['error' => 'Error de validacion', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error en LoginController@login:', ['exception' => $e]);
            return response()->json(['error' => 'Error al procesar la solicitud'], 500);
        }
    }
}
