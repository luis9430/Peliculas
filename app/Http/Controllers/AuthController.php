<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $response = Http::post('http://peliculas.test/api/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return response()->json(['token' => $data['token']]);
        }

        return response()->json(['error' => 'Credenciales incorrectas'], 401);
    }
}
