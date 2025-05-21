<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bodegas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class bodegasController extends Controller
{
    public function index(){
        $bodegas = Bodegas::all();

        if($bodegas -> isEmpty()){
            $data = [
                'message' => 'No se encontro ninguna bodega.',
                'status' => 200
            ];

            return response()->json($data, 404);
        }

        return response()->json($bodegas, 200);
    }

    public function getActiveBodegas() {
        $bodegas = Bodegas::where('active', true)->get();

        if($bodegas -> isEmpty()){
            $data = [
                'message' => 'No se encontro ningua bodega.',
                'status' => 200
            ];

            return response()->json($data, 404);
        }

        return response()->json($bodegas, 200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'codigo' => 'required|max:255',
            'active' => 'boolean',
            'dataJson' => 'required|array'
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $bodegas = Bodegas::create([
            'codigo' => $request->codigo,
            'active' => $request->active,
            'dataJson' => $request->dataJson
        ]);

        if(!$bodegas) {
            $data = [
                'message' => 'Error al crear la bodega',
                'status' => 500
            ];

            return response()->json($data, 500);
        }

        return response()->json($bodegas, 201);
    }

    public function show($id){
        $bodega = Bodegas::find($id);

        if(!$bodega) {
            $data = [
                'message' => 'Bodega no encontrada',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        return response()->json($bodega, 200);
    }

    public function inactivate($id){
        $bodega = Bodegas::find($id);

        if(!$bodega) {
            $data = [
                'message' => 'Bodega no encontrada',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $bodega->delete();

        $data = [
            'message' => 'Bodega eliminada correctamente',
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(),[
            'codigo' => 'required|max:255',
            'active' => 'boolean',
            'dataJson' => 'array'
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $bodega = Bodegas::find($id);

        if(!$bodega) {
            $data = [
                'message' => 'Bodega no encontrada',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $bodega->update([
            'codigo' => $request->codigo,
            'active' => $request->active,
            'dataJson' => $request->dataJson
        ]);

        return response()->json($bodega, 200);
    }

    public function updatePartial(Request $request, $id){
        $bodega = Bodegas::find($id);

        if(!$bodega){
            $data = [
                'message' => 'Bodega no encontrada',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(),[
            'codigo' => 'max:255',
            'active' => 'boolean',
            'dataJson' => 'array'
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        if( $request->has('codigo') ){
            $bodega->codigo = $request->codigo;
        }

        if( $request->has('active') ){
            $bodega->active = $request->active;
        }

        if( $request->has('dataJson') ){
            $bodega->dataJson = $request->dataJson;
        }

        $bodega->save();

        $data = [
            'message' => 'Bodega actualizada',
            'servicio' => $bodega,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function updateBodegaSlotStatus($id, $slot, $status, $trackingcode) {
        $bodega = Bodegas::findOrFail($id);
        $dataJson = $bodega->dataJson;

        foreach ($dataJson as &$group) {
            // Si tus items están en un sub-array 'datos'
            if (isset($group['datos']) && is_array($group['datos'])) {
                foreach ($group['datos'] as &$item) {
                    // Cuando coincida el value
                    if (isset($item['value']) && $item['value'] === $slot) {
                        $item['status'] = $status;
                        $item['tracking_code'] = $trackingcode;
                    }
                }
                unset($item);
            }
    
        }

        unset($group);

        // 4. Asigna el array modificado de vuelta
        $bodega->dataJson = $dataJson;

        // 5. Guarda el modelo
        $bodega->save();

        // 6. Devuelve la bodega actualizada
        return response()->json($bodega, 200);
    }
}
