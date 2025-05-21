<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bodegas extends Model
{
    use HasFactory;

    // Especifica la tabla, en caso de no seguir la convención de plural (si el modelo se llama Bodega y la tabla es bodegas)
    protected $table = 'bodegas';

    protected $fillable = [
        'codigo',
        'active',
        'dataJson'  // Aquí se almacena el JSON con la estructura de datos (ej. BodegaDataJsonElement[])
    ];

    protected $casts = [
        'active'   => 'boolean',
        // Con este casting, Laravel convertirá automáticamente el contenido JSON almacenado en la columna a un array (y viceversa)
        'dataJson' => 'array'
    ];
}
