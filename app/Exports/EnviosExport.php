<?php

namespace App\Exports;

use App\Models\Envios;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EnviosExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Envios::with(['servicio','bodega'])->get();
    }

        /**
     * Mapea cada envío a una fila del CSV/Excel.
     *
     * @param  \App\Models\Envios  $envio
     * @return array
     */
    public function map($envio): array
    {
        return [
            $envio->id,
            $envio->tracking_id,
            $envio->descripcion,
            $envio->status,
            $envio->peso,
            $envio->nombre_remitente,
            $envio->nombre_receptor,
            // En lugar de IDs, usamos los valores de las relaciones:
            $envio->servicio->name    ?? '',
            $envio->bodega->codigo    ?? '',
            $envio->departamento,
            $envio->direccion,
            // Fecha formateada:
            $envio->created_at->format('Y-m-d H:i:s'),
        ];
    }

      /**
     * Define la fila de encabezados.
     *
     * @return string[]
     */
    public function headings(): array
    {
        return [
            'ID',
            'Tracking ID',
            'Descripción',
            'Status',
            'Peso',
            'Remitente',
            'Receptor',
            'Servicio',      // Name en lugar de servicio_id
            'Bodega',        // Código en lugar de bodega_id
            'Departamento',
            'Dirección',
            'Fecha Creación'
        ];
    }
}
