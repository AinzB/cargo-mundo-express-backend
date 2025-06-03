<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class usuariosController extends Controller
{
    public function index(){
        $usuarios = Usuarios::orderBy('active', 'desc')->get();

        if($usuarios -> isEmpty()){
            $data = [
                'message' => 'No se encontro ningun usuarios.',
                'status' => 200
            ];

            return response()->json($data, 404);
        }

        return response()->json($usuarios, 200);
    }

    public function getUsuariosActivos($filtro) {
        $filtroUsuarios = $filtro == 'true' ? 1 : 0;
        $usuarios = Usuarios::where('active', $filtroUsuarios)->get();

        if($usuarios -> isEmpty()){
            $data = [
                'message' => 'No se encontro ningun usuario.',
                'status' => 200
            ];

            return response()->json($data, 404);
        }

        return response()->json($usuarios, 200);
    }

    public function store(Request $request){
        $defaultPassword = 'cargoMundoExpress2025';

        $validator = Validator::make($request->all(),[
            'username' => 'required|max:255',
            'email' => 'required|email|unique:usuarios',
            'role' => 'required|in:Admin,Editor,Vista',
            'genre' => 'required|in:male,female'
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $usuarios = Usuarios::create([
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($defaultPassword),
            'genre' => $request->genre
        ]);

        if(!$usuarios) {
            $data = [
                'message' => 'Error al crear el usuario',
                'status' => 500
            ];

            return response()->json($data, 500);
        }

        $data = [
            'usuario' => $usuarios,
            'status' => 201
        ];

        return response()->json($data, 201);
    }

    public function show($id){
        $usuario = Usuarios::find($id);

        if(!$usuario){
            $data = [
                'message' => 'Usuario no encontrado',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $data = [
            'usuario' => $usuario,
            'status' => 200
        ];

        return response()->json($usuario, 200);
    }

    public function inactivate($id){
        $usuario = Usuarios::find($id);

        if(!$usuario){
            $data = [
                'message' => 'Usuario no encontrado',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $usuario->active = false;
        $usuario->save();

        $data = [
            'message' => 'Usuario inactivado',
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function activarUsuario($id){
        $usuario = Usuarios::find($id);

        if(!$usuario){
            $data = [
                'message' => 'Usuario no encontrado',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $usuario->active = true;
        $usuario->save();

        $data = [
            'message' => 'Usuario activado',
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id){
        $usuario = Usuarios::find($id);

        if(!$usuario){
            $data = [
                'message' => 'Usuario no encontrado',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'username' => 'required|max:255',
            'email' => 'required|email|unique:usuarios',
            'role' => 'required|in:Admin,Editor,Vista',
            'genre' => 'required|in:male,female'
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->role = $request->role;
        $usuario->password = $request->password;
        $usuario->genre = $request->genre;

        $usuario->save();

        $data = [
            'message' => 'Usuario actualizado',
            'usuario' => $usuario,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function updatePartial(Request $request, $id){
        $usuario = Usuarios::find($id);

        if(!$usuario){
            $data = [
                'message' => 'Usuario no encontrado',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'username' => 'max:255',
            'email' => 'email',
            'role' => 'in:Admin,Editor,Vista',
            'active' => 'boolean',
            'genre' => 'in:male,female'
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        if( $request->has('username') ){
            $usuario->username = $request->username;
        }

        if( $request->has('email') && $request->email != $usuario->email ){
            $usuario->email = $request->email;
        }

        if( $request->has('role') ){
            $usuario->role = $request->role;
        }

        if( $request->has('password') ){
            $usuario->password = Hash::make($request->password);
        }

        if( $request->has('active') ){
            $usuario->active = $request->active;
        }

        if( $request->has('genre') ){
            $usuario->genre = $request->genre;
        }

        $usuario->save();

        $data = [
            'message' => 'Usuario actualizado',
            'usuario' => $usuario,
            'status' => 200
        ];

        return response()->json($data, 200);
    }
}
