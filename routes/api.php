<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsuariosController;
use App\Http\Controllers\Api\ServiciosController;
use App\Http\Controllers\Api\BodegasController;
use App\Http\Controllers\Api\EnviosController;
use App\Http\Controllers\Api\TicketController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí definimos las rutas de nuestra API. Todas las rutas que requieren
| un usuario autenticado mediante Sanctum van dentro del middleware auth:sanctum.
|
*/

// Ruta para obtener el usuario autenticado
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas protegidas por Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Usuarios
    Route::get   ('/usuarios',                       [UsuariosController::class, 'index']);
    Route::get   ('/usuarios/filtraractivos/{filtro}', [UsuariosController::class, 'getUsuariosActivos']);
    Route::get   ('/usuarios/{id}',                  [UsuariosController::class, 'show']);
    Route::post  ('/usuarios',                       [UsuariosController::class, 'store']);
    Route::post  ('/usuarios/activarusuario/{id}',   [UsuariosController::class, 'activarUsuario']);
    Route::put   ('/usuarios/{id}',                  [UsuariosController::class, 'update']);
    Route::patch ('/usuarios/{id}',                  [UsuariosController::class, 'updatePartial']);
    Route::delete('/usuarios/{id}',                  [UsuariosController::class, 'inactivate']);

    // Servicios
    Route::get   ('/servicios',                      [ServiciosController::class, 'index']);
    Route::get   ('/servicios/getactive',            [ServiciosController::class, 'getActiveServices']);
    Route::get   ('/servicios/getfiltro/{filtro}',   [ServiciosController::class, 'getServiciosTransporteFiltrado']);
    Route::get   ('/servicios/{id}',                 [ServiciosController::class, 'show']);
    Route::post  ('/servicios',                      [ServiciosController::class, 'store']);
    Route::put   ('/servicios/{id}',                 [ServiciosController::class, 'update']);
    Route::patch ('/servicios/{id}',                 [ServiciosController::class, 'updatePartial']);
    Route::delete('/servicios/{id}',                 [ServiciosController::class, 'inactivate']);

    // Bodegas
    Route::get   ('/bodegas',                        [BodegasController::class, 'index']);
    Route::get   ('/bodegas/getactive',              [BodegasController::class, 'getActiveBodegas']);
    Route::get   ('/bodegas/{id}',                   [BodegasController::class, 'show']);
    Route::post  ('/bodegas',                        [BodegasController::class, 'store']);
    Route::put   ('/bodegas/{id}',                   [BodegasController::class, 'update']);
    Route::patch ('/bodegas/{id}',                   [BodegasController::class, 'updatePartial']);
    Route::delete('/bodegas/{id}',                   [BodegasController::class, 'inactivate']);

    // Envíos
    Route::get   ('/envios',                         [EnviosController::class, 'index']);
    Route::get   ('/envios/getactive',               [EnviosController::class, 'getActiveEnvios']);
    Route::get   ('/envios/getfiltro/{filtro}',      [EnviosController::class, 'getEnviosStatusFiltrado']);
    Route::get   ('/envios/{id}',                    [EnviosController::class, 'show']);
    Route::post  ('/envios',                         [EnviosController::class, 'store']);
    Route::put   ('/envios/{id}',                    [EnviosController::class, 'update']);
    Route::patch ('/envios/{id}',                    [EnviosController::class, 'updatePartial']);
    Route::delete('/envios/{id}',                    [EnviosController::class, 'inactivate']);
    Route::get   ('/envios/getresumen/{period}',     [EnviosController::class, 'getResumenEnvios']);
    Route::get   ('/envios/export/csv',              [EnviosController::class, 'exportCsv']);
    Route::get   ('/envios/export/xlsx',             [EnviosController::class, 'exportXlsx']);

    // Tickets
    Route::post  ('/tickets',                        [TicketController::class, 'generarTicket']);
    Route::post  ('/tickets/second',                 [TicketController::class, 'generarSecondTicket']);
});
