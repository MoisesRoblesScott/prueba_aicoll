<?php

use App\Http\Controllers\EmpresaController;
use Illuminate\Support\Facades\Route;

// Listar empresas
Route::get('empresas', [EmpresaController::class, 'listarEmpresas']);

// Crear nueva empresa
Route::post('/empresas', [EmpresaController::class, 'registrarEmpresa']);

// Consultar empresa por NIT
Route::get('/empresas/{nit}', [EmpresaController::class, 'consultarEmpresaPorNit']);

// Actualizar empresa por NIT
Route::put('/empresas/{nit}', [EmpresaController::class, 'actualizarEmpresa']);

// Eliminar empresa por NIT
Route::delete('/empresas/{nit}', [EmpresaController::class, 'eliminarEmpresa']);
