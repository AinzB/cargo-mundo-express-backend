<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;   
use Illuminate\Notifications\Notifiable;                   
use Laravel\Sanctum\HasApiTokens;                         
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuarios extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

    protected $table = 'usuarios';

    protected $fillable = [
        'username',
        'email',
        'role',
        'password',
        'active',
        'genre'
    ];

    /**
     * Oculta atributos al serializar el modelo.
     *
     * @var array<string>
     */
    protected $hidden = ['password', 'remember_token'];  // ← Corrección aquí :contentReference[oaicite:2]{index=2}

    /**
     * Casteo de atributos a tipos nativos.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'active' => 'boolean',
        'email_verified_at' => 'datetime',
    ];
}
