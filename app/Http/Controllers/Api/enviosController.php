<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\bodegasController;
use App\Models\Envios;
use App\Models\Servicios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Exports\EnviosExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as BaseExcel;

class enviosController extends Controller
{
    protected $bodegasController;

    public function __construct()
    {
        $this->bodegasController = new bodegasController();
    }

    public function index(){
        $envios = Envios::with(['servicio', 'bodega', 'createdBy', 'updatedBy'])
        ->get();

        if($envios -> isEmpty()){
            $data = [
                'message' => 'No se encontro ningun envio.',
                'status' => 200
            ];

            return response()->json($data, 404);
        }

        return response()->json($envios, 200);
    }

    public function getActiveEnvios() {
        $envios = Envios::with(['servicio', 'bodega', 'createdBy', 'updatedBy'])
        ->where('status', '!=', 'Entregado')
        ->get();

        if($envios -> isEmpty()){
            $data = [
                'message' => 'No se encontro ningun envio.',
                'status' => 200
            ];

            return response()->json($data, 404);
        }

        return response()->json($envios, 200);
    }

    public function getEnviosStatusFiltrado($filtro) {
        $envios = Envios::with(['servicio', 'bodega', 'createdBy', 'updatedBy'])
        ->where('status', $filtro)
        ->get();

        if($envios -> isEmpty()){
            $data = [
                'message' => 'No se encontro ningun envio.',
                'status' => 200
            ];

            return response()->json($data, 404);
        }

        return response()->json($envios, 200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'tracking_id' => 'required',
            'descripcion' => 'max:255',
            'status' => 'required|in:bodegaUSA,bodegaNicaragua,Recibido,EnCamino,Delivery,Entregado,Retrasado',
            'peso' => 'numeric',
            'nombre_remitente' => 'max:255',
            'email_remitente' => 'max:100',
            'numero_remitente' => 'max:20',
            'nombre_receptor' => 'max:255',
            'email_receptor' => 'max:100',
            'numero_receptor' => 'max:20',
            'entrega' => 'in:oficina,delivery_managua,delivery_nicaragua',
            'departamento' => 'max:50',
            'direccion' => 'max:255',
            'bodega_etiqueta' => 'max:10',
            // Relaciones (se asume que se envían como IDs)
            'servicio_id' => 'required|exists:servicios,id',
            'bodega_id' => 'required|exists:bodegas,id',
            'created_by_id' => 'nullable|exists:usuarios,id',
            'updated_by_id' => 'nullable|exists:usuarios,id'
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $saveBodega = $this->bodegasController->updateBodegaSlotStatus($request->bodega_id, $request->bodega_etiqueta, 'Ocupado', $request->tracking_id);

        $envios = Envios::create([
            'tracking_id' => $request->tracking_id,
            'descripcion' => $request->descripcion,
            'status' => $request->status,
            'peso' => $request->peso,
            'nombre_remitente' => $request->nombre_remitente,
            'email_remitente' => $request->email_remitente,
            'numero_remitente' => $request->numero_remitente,
            'nombre_receptor' => $request->nombre_receptor,
            'email_receptor' => $request->email_receptor,
            'numero_receptor' => $request->numero_receptor,
            'entrega' => $request->entrega,
            'departamento' => $request->departamento,
            'direccion' => $request->direccion,
            'bodega_etiqueta' => $request->bodega_etiqueta,
            // Relaciones (se asume que se envían como IDs)
            'servicio_id' => $request->servicio_id,
            'bodega_id' => $request->bodega_id,
            'created_by_id' => $request->created_by_id,
            'updated_by_id' => $request->updated_by_id
        ]);

        if(!$envios) {
            $data = [
                'message' => 'Error al crear el envio',
                'status' => 500
            ];

            return response()->json($data, 500);
        }

        $envios->load('servicio', 'bodega', 'createdBy', 'updatedBy');

        return response()->json($envios, 201);
    }

    public function show($id){
        $envios = Envios::with(['servicio', 'bodega', 'createdBy', 'updatedBy'])
        ->find($id);

        if(!$envios) {
            $data = [
                'message' => 'Envio no encontrado',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        return response()->json($envios, 200);
    }

    public function inactivate($id){
        $envios = Envios::find($id);

        if(!$envios) {
            $data = [
                'message' => 'Envio no encontrado',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $envios->delete();

        $data = [
            'message' => 'Envio eliminado correctamente.',
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(),[
            'tracking_id' => 'required',
            'descripcion' => 'max:255',
            'status' => 'required|in:bodegaUSA,bodegaNicaragua,Recibido,EnCamino,Delivery,Entregado,Retrasado',
            'peso' => 'numeric',
            'nombre_remitente' => 'max:255',
            'email_remitente' => 'max:100',
            'numero_remitente' => 'max:20',
            'nombre_receptor' => 'max:255',
            'email_receptor' => 'max:100',
            'numero_receptor' => 'max:20',
            'entrega' => 'in:oficina,delivery_managua,delivery_nicaragua',
            'departamento' => 'max:50',
            'direccion' => 'max:255',
            'bodega_etiqueta' => 'max:10',
            // Relaciones (se asume que se envían como IDs)
            'servicio_id' => 'required|exists:servicios,id',
            'bodega_id' => 'required|exists:bodegas,id',
            'created_by_id' => 'nullable|exists:usuarios,id',
            'updated_by_id' => 'nullable|exists:usuarios,id'
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $envios = Envios::find($id);

        if(!$envios) {
            $data = [
                'message' => 'Envio no encontrado',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $envios->update([
            'tracking_id' => $request->tracking_id,
            'descripcion' => $request->descripcion,
            'status' => $request->status,
            'peso' => $request->peso,
            'nombre_remitente' => $request->nombre_remitente,
            'email_remitente' => $request->email_remitente,
            'numero_remitente' => $request->numero_remitente,
            'nombre_receptor' => $request->nombre_receptor,
            'email_receptor' => $request->email_receptor,
            'numero_receptor' => $request->numero_receptor,
            'entrega' => $request->entrega,
            'departamento' => $request->departamento,
            'direccion' => $request->direccion,
            'bodega_etiqueta' => $request->bodega_etiqueta,
            // Relaciones (se asume que se envían como IDs)
            'servicio_id' => $request->servicio_id,
            'bodega_id' => $request->bodega_id,
            'created_by_id' => $request->created_by_id,
            'updated_by_id' => $request->updated_by_id
        ]);

        $envios->load('servicio', 'bodega', 'createdBy', 'updatedBy');

        return response()->json($envios, 200);
    }

    public function updatePartial(Request $request, $id){
        $envios = Envios::find($id);

        if(!$envios){
            $data = [
                'message' => 'Envio no encontrado',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(),[
            'descripcion' => 'max:255',
            'status' => 'in:bodegaUSA,bodegaNicaragua,Recibido,EnCamino,Delivery,Entregado,Retrasado',
            'peso' => 'numeric',
            'nombre_remitente' => 'max:255',
            'email_remitente' => 'max:100',
            'numero_remitente' => 'max:20',
            'nombre_receptor' => 'max:255',
            'email_receptor' => 'max:100',
            'numero_receptor' => 'max:20',
            'entrega' => 'in:oficina,delivery_managua,delivery_nicaragua',
            'departamento' => 'max:50',
            'direccion' => 'max:255',
            'bodega_etiqueta' => 'max:10',
            // Relaciones (se asume que se envían como IDs)
            'servicio_id' => 'exists:servicios,id',
            'bodega_id' => 'required|exists:bodegas,id',
            'created_by_id' => 'nullable|exists:usuarios,id',
            'updated_by_id' => 'nullable|exists:usuarios,id'
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        if( $request->has('tracking_id') ){
            $envios->tracking_id = $request->tracking_id;
        }

        if( $request->has('descripcion') ){
            $envios->descripcion = $request->descripcion;
        }

        if( $request->has('status') ){
            $envios->status = $request->status;
        }

        if( $request->has('peso') ){
            $envios->peso = $request->peso;
        }

        if( $request->has('nombre_remitente') ){
            $envios->nombre_remitente = $request->nombre_remitente;
        }

        if( $request->has('email_remitente') ){
            $envios->email_remitente = $request->email_remitente;
        }

        if( $request->has('numero_remitente') ){
            $envios->numero_remitente = $request->numero_remitente;
        }

        if( $request->has('nombre_receptor') ){
            $envios->nombre_receptor = $request->nombre_receptor;
        }

        if( $request->has('email_receptor') ){
            $envios->email_receptor = $request->email_receptor;
        }

        if( $request->has('numero_receptor') ){
            $envios->numero_receptor = $request->numero_receptor;
        }

        if( $request->has('entrega') ){
            $envios->entrega = $request->entrega;
        }

        if( $request->has('departamento') ){
            $envios->departamento = $request->departamento;
        }

        if( $request->has('direccion') ){
            $envios->direccion = $request->direccion;
        }

        if( $request->has('bodega_etiqueta') ){
            $envios->bodega_etiqueta = $request->bodega_etiqueta;
        }

        if( $request->has('servicio_id') ){
            $envios->servicio_id = $request->servicio_id;
        }

        if( $request->has('bodega_id') ){
            $envios->bodega_id = $request->bodega_id;
        }

        if( $request->has('created_by_id') ){
            $envios->created_by_id = $request->created_by_id;
        }

        if( $request->has('updated_by_id') ){
            $envios->updated_by_id = $request->updated_by_id;
        }

        if(  $envios->status == 'Entregado' ){
            $saveBodega = $this->bodegasController->updateBodegaSlotStatus($request->bodega_id, $request->bodega_etiqueta, 'Disponible', '');
        }

        $envios->save();

        $envios->load('servicio', 'bodega', 'createdBy', 'updatedBy');

        $data = [
            'message' => 'Envio actualizado',
            'servicio' => $envios,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function getResumenEnvios($period) {
        // Inicialmente, definimos la consulta base
        $query = Envios::query();

        // Si se definió un período, obtenemos las fechas de inicio y fin
        if ($period) {
            list($startDate, $endDate) = $this->getDateRange($period);

            // Si obtenemos un rango válido, aplicamos el filtro
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        }

        // Ahora agregamos los eager loading para las relaciones (si lo requieres en el resumen)
        $query->with(['servicio', 'bodega', 'createdBy', 'updatedBy']);

        // Total de envíos con filtro de fecha (si aplica)
        $enviosTotales = $query->count();

        // Envios en tránsito: status igual a 'En Camino'
        $enviosEnTransito = (clone $query)->where('status', 'En Camino')->count();

        // Listos para retirar: status igual a 'bodegaUSA' o 'bodegaNicaragua'
        $listosParaRetirar = (clone $query)->whereIn('status', ['bodegaUSA', 'bodegaNicaragua'])->count();

        // Envios en Bodegas USA
        $enBodegasUSA = (clone $query)->where('status', 'bodegaUSA')->count();

        // Envios en Bodega Nicaragua
        $enBodegaNicaragua = (clone $query)->where('status', 'bodegaNicaragua')->count();

        // Envios en Delivery: status igual a 'Delivery enviado'
        $enDelivery = (clone $query)->where('status', 'Delivery enviado')->count();

        // Peso promedio de envíos
        $pesoPromedio = round((clone $query)->avg('peso'), 2);

        // Obtener el servicio más frecuente utilizado en los envíos:
        $servicioMasFrecuenteGroup = (clone $query)->select('servicio_id', DB::raw('COUNT(*) as total'))
            ->groupBy('servicio_id')
            ->orderByDesc('total')
            ->first();

        $servicioMasFrecuente = null;
        $envioMasFrecuenteTransporte = null;
        if ($servicioMasFrecuenteGroup) {
            $servicioMasFrecuente = Servicios::find($servicioMasFrecuenteGroup->servicio_id);
            $envioMasFrecuenteTransporte = $servicioMasFrecuente ? $servicioMasFrecuente->transporte : null;
        }

        $data = [
            'envios_totales'         => $enviosTotales,
            'envios_en_transito'     => $enviosEnTransito,
            'listos_para_retirar'    => $listosParaRetirar,
            'en_bodegas_usa'         => $enBodegasUSA,
            'en_bodega_nicaragua'    => $enBodegaNicaragua,
            'en_delivery'            => $enDelivery,
            'servicio_mas_frecuente' => $servicioMasFrecuente,
            'envio_mas_frecuente'    => $envioMasFrecuenteTransporte,
            'peso_promedio'          => $pesoPromedio,
        ];

        return response()->json($data, 200);
    }

    private function getDateRange($period) {
        $now = Carbon::now();

        switch (strtolower($period)) {
            case 'diario':
                $start = $now->copy()->startOfDay();
                $end   = $now->copy()->endOfDay();
                break;
            case 'ayer':
                $start = $now->copy()->subDay()->startOfDay();
                $end   = $now->copy()->subDay()->endOfDay();
                break;
            case 'semanal': // Esta semana
                $start = $now->copy()->startOfWeek();
                $end   = $now->copy()->endOfWeek();
                break;
            case 'mensual': // Este mes
                $start = $now->copy()->startOfMonth();
                $end   = $now->copy()->endOfMonth();
                break;
            case 'anual': // Este año
                $start = $now->copy()->startOfYear();
                $end   = $now->copy()->endOfYear();
                break;
            case 'lastyear':
                $start = $now->copy()->subYear()->startOfYear();
                $end   = $now->copy()->subYear()->endOfYear();
                break;
            case 'lastmonth':
                $start = $now->copy()->subMonth()->startOfMonth();
                $end   = $now->copy()->subMonth()->endOfMonth();
                break;
            case 'lastweek':
                $start = $now->copy()->subWeek()->startOfWeek();
                $end   = $now->copy()->subWeek()->endOfWeek();
                break;
            default:
                // Si no se proporciona o no coincide, puedes decidir no filtrar por fecha
                $start = null;
                $end = null;
                break;
        }

        return [$start, $end];
    }

    // Exportar CSV
    public function exportCsv()
    {
        return Excel::download(new EnviosExport, 'envios.csv', BaseExcel::CSV);
    }

    // Exportar Excel (.xlsx)
    public function exportXlsx()
    {
        return Excel::download(new EnviosExport, 'envios.xlsx', BaseExcel::XLSX);
    }
}
