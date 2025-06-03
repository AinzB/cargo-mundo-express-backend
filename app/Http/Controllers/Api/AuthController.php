<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validación de entrada
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // Intentar autenticar con el guard web (sesión)
        if (! Auth::attempt($credentials)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        // Regenerar la sesión para evitar fijación de sesión
        $request->session()->regenerate();

        // Respuesta sin contenido (204 No Content)
        return response()->noContent();
    }

    public function logout(Request $request)
    {
        // Logout del guard web (para Sanctum SPA auth)
        Auth::guard('web')->logout();

        // Invalidar y regenerar token CSRF y sesión
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Respuesta sin contenido
        return response()->noContent();
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
