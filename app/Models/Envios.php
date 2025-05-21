<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Envios extends Model
{
    use HasFactory;

    // Especifica el nombre de la tabla en la base de datos
    protected $table = 'envios';

    /**
     * Las columnas que se pueden asignar masivamente.
     *
     * Es importante incluir los campos de relaciones como "servicio_id" y "bodega_id".
     */
    protected $fillable = [
        'tracking_id',
        'descripcion',
        'status',
        'peso',
        'nombre_remitente',
        'email_remitente',
        'numero_remitente',
        'nombre_receptor',
        'email_receptor',
        'numero_receptor',
        'entrega',
        'departamento',
        'direccion',
        'bodega_etiqueta',
        // Relaciones (se asume que se envían como IDs)
        'servicio_id',
        'bodega_id',
        'created_by_id',
        'updated_by_id'
    ];

    /**
     * Relación con el modelo Servicio.
     * Se asume que existe una tabla "servicios" y el campo foráneo es "servicio_id".
     */
    public function servicio()
    {
        return $this->belongsTo(Servicios::class, 'servicio_id');
    }

    /**
     * Relación con el modelo Bodegas.
     * Se asume que la tabla se llama "bodegas" y se utiliza "bodega_id" como foráneo.
     */
    public function bodega()
    {
        return $this->belongsTo(Bodegas::class, 'bodega_id');
    }

    /**
     * Relación para el usuario que crea el envío.
     */
    public function createdBy()
    {
        return $this->belongsTo(Usuarios::class, 'created_by_id');
    }

    /**
     * Relación para el usuario que actualiza el envío.
     */
    public function updatedBy()
    {
        return $this->belongsTo(Usuarios::class, 'updated_by_id');
    }
}
