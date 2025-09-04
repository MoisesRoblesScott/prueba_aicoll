<?php

use App\Http\Controllers\EmpresaController;
use Illuminate\Support\Facades\Route;


Route::get('empresas', [EmpresaController::class, 'index']);

// Crear nueva empresa
Route::post('/empresas', [EmpresaController::class, 'store']);

// Consultar empresa por NIT
Route::get('/empresas/{nit}', [EmpresaController::class, 'show']);

// Actualizar empresa por NIT
Route::put('/empresas/{nit}', [EmpresaController::class, 'update']);

// Eliminar empresa por NIT
Route::delete('/empresas/{nit}', [EmpresaController::class, 'destroy']);
