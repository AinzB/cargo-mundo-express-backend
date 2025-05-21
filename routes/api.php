<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\usuariosController;
use App\Http\Controllers\Api\serviciosController;
use App\Http\Controllers\Api\bodegasController;
use App\Http\Controllers\Api\enviosController;
use App\Http\Controllers\Api\ticketController;

// Usuarios
Route::get('/usuarios', [usuariosController::class, 'index']);
Route::get('/usuarios/filtraractivos/{filtro}', [usuariosController::class, 'getUsuariosActivos']);
Route::get('/usuarios/{id}', [usuariosController::class, 'show']);
Route::post('/usuarios', [usuariosController::class, 'store']);
Route::post('/usuarios/activarusuario/{id}', [usuariosController::class, 'activarUsuario']);
Route::put('/usuarios/{id}', [usuariosController::class, 'update']);
Route::patch('/usuarios/{id}', [usuariosController::class, 'updatePartial']);
Route::delete('/usuarios/{id}', [usuariosController::class, 'inactivate']);

Route::get('/servicios', [serviciosController::class, 'index']);
Route::get('/servicios/getactive', [serviciosController::class, 'getActiveServices']);
Route::get('/servicios/getfiltro/{filtro}', [serviciosController::class, 'getServiciosTransporteFiltrado']);
Route::get('/servicios/{id}', [serviciosController::class, 'show']);
Route::post('/servicios', [serviciosController::class, 'store']);
Route::put('/servicios/{id}', [serviciosController::class, 'update']);
Route::patch('/servicios/{id}', [serviciosController::class, 'updatePartial']);
Route::delete('/servicios/{id}', [serviciosController::class, 'inactivate']);

Route::get('/bodegas', [bodegasController::class, 'index']);
Route::get('/bodegas/getactive', [bodegasController::class, 'getActiveBodegas']);
Route::get('/bodegas/{id}', [bodegasController::class, 'show']);
Route::post('/bodegas', [bodegasController::class, 'store']);
Route::put('/bodegas/{id}', [bodegasController::class, 'update']);
Route::patch('/bodegas/{id}', [bodegasController::class, 'updatePartial']);
Route::delete('/bodegas/{id}', [bodegasController::class, 'inactivate']);

// Envios
Route::get('/envios', [enviosController::class, 'index']);
Route::get('/envios/getactive', [enviosController::class, 'getActiveEnvios']);
Route::get('/envios/getfiltro/{filtro}', [enviosController::class, 'getEnviosStatusFiltrado']);
Route::get('/envios/{id}', [enviosController::class, 'show']);
Route::post('/envios', [enviosController::class, 'store']);
Route::put('/envios/{id}', [enviosController::class, 'update']);
Route::patch('/envios/{id}', [enviosController::class, 'updatePartial']);
Route::delete('/envios/{id}', [enviosController::class, 'inactivate']);
Route::get('/envios/getresumen/{period}', [enviosController::class, 'getResumenEnvios']);
Route::get('/envios/export/csv', [enviosController::class, 'exportCsv']);
Route::get('/envios/export/xlsx', [enviosController::class, 'exportXlsx']);

// Tickets
Route::post('/tickets', [ticketController::class, 'generarTicket']);
Route::post('/tickets/second', [ticketController::class, 'generarSecondTicket']);
