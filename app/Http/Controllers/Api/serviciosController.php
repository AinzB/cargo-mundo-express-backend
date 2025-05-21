<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Servicios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class serviciosController extends Controller
{
    public function index(){
        $servicios = Servicios::all();

        if($servicios -> isEmpty()){
            $data = [
                'message' => 'No se encontro ningun servicio.',
                'status' => 200
            ];

            return response()->json($data, 404);
        }

        return response()->json($servicios, 200);
    }

    public function getActiveServices() {
        $servicios = Servicios::where('active', true)->get();

        if($servicios -> isEmpty()){
            $data = [
                'message' => 'No se encontro ningun servicio.',
                'status' => 200
            ];

            return response()->json($data, 404);
        }

        return response()->json($servicios, 200);
    }

    public function getServiciosTransporteFiltrado($filtro) {
        $servicios = Servicios::where('transporte', $filtro)
        ->get();

        if($servicios -> isEmpty()){
            $data = [
                'message' => 'No se encontro ningun servicio.',
                'status' => 200
            ];

            return response()->json($data, 404);
        }

        return response()->json($servicios, 200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'transporte' => 'required|max:20|in:Maritimo,Aereo',
            'duracion' => 'required|max:10',
            'priceperlb' => 'numeric',
            'extraprice' => 'numeric'
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $servicios = Servicios::create([
            'name' => $request->name,
            'description' => $request->description,
            'transporte' => $request->transporte,
            'duracion' => $request->duracion,
            'priceperlb' => $request->priceperlb,
            'extraprice' => $request->extraprice
        ]);

        if(!$servicios) {
            $data = [
                'message' => 'Error al crear el servicio',
                'status' => 500
            ];

            return response()->json($data, 500);
        }

        return response()->json($servicios, 201);
    }

    public function show($id){
        $servicio = Servicios::find($id);

        if(!$servicio) {
            $data = [
                'message' => 'Servicio no encontrado',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        return response()->json($servicio, 200);
    }

    public function inactivate($id){
        $servicio = Servicios::find($id);

        if(!$servicio) {
            $data = [
                'message' => 'Servicio no encontrado',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $servicio->delete();

        $data = [
            'message' => 'Servicio eliminado correctamente',
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(),[
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'transporte' => 'required|max:20|in:Maritimo,Aereo',
            'duracion' => 'required|max:10',
            'priceperlb' => 'numeric',
            'extraprice' => 'numeric'
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $servicio = Servicios::find($id);

        if(!$servicio) {
            $data = [
                'message' => 'Servicio no encontrado',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $servicio->update([
            'name' => $request->name,
            'description' => $request->description,
            'transporte' => $request->transporte,
            'duracion' => $request->duracion,
            'priceperlb' => $request->priceperlb,
            'extraprice' => $request->extraprice
        ]);

        return response()->json($servicio, 200);
    }

    public function updatePartial(Request $request, $id){
        $servicio = Servicios::find($id);

        if(!$servicio){
            $data = [
                'message' => 'Servicio no encontrado',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'max:255',
            'description' => 'max:255',
            'transporte' => 'max:20|in:Maritimo,Aereo',
            'duracion' => 'max:10',
            'priceperlb' => 'numeric',
            'extraprice' => 'numeric',
            'active' => 'boolean',
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        if( $request->has('name') ){
            $servicio->name = $request->name;
        }

        if( $request->has('description') ){
            $servicio->description = $request->description;
        }

        if( $request->has('transporte') ){
            $servicio->transporte = $request->transporte;
        }

        if( $request->has('duracion') ){
            $servicio->duracion = $request->duracion;
        }

        if( $request->has('priceperlb') ){
            $servicio->priceperlb = $request->priceperlb;
        }

        if( $request->has('extraprice') ){
            $servicio->extraprice = $request->extraprice;
        }

        if( $request->has('active') ){
            $servicio->active = $request->active;
        }

        $servicio->save();

        $data = [
            'message' => 'Servicio actualizado',
            'servicio' => $servicio,
            'status' => 200
        ];

        return response()->json($data, 200);
    }
}
