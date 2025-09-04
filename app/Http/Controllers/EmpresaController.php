<?php

namespace App\Http\Controllers;

use App\Services\EmpresaService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class EmpresaController extends Controller
{
    protected $empresaService;

    public function __construct(EmpresaService $empresaService)
    {
        $this->empresaService = $empresaService;
    }

    public function index()
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $this->empresaService->listar()
            ]);
        } catch (Exception $e) {
            return $this->respuestaError($e);
        }
    }

    public function show($nit)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $this->empresaService->consultarPorNit($nit)
            ]);
        } catch (ModelNotFoundException $e) { // controla errores cuando se hace busqueda de registros
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ], 404
            );
        } catch (Exception $e) { //controla otros tipos de errores a nivel del servidor.
            return $this->respuestaError($e);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate(
                [
                    'nit'       => 'required|string|max:9|unique:empresas,nit',
                    'nombre'    => 'required|string|max:255',
                    'direccion' => 'required|string|max:255',
                    'telefono'  => 'required|string|max:11',
                ]
            );

            $empresa = $this->empresaService->crear($validated);
            return response()->json(['success' => true, 'data' => $empresa], 201);
        } catch (\Illuminate\Validation\ValidationException $e) { //controlar errores de validacion.
            return response()->json([
                'success' => false,
                'message' => 'Error de validación en los datos enviados.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) { //controla otros tipos de errores a nivel del servidor.
            return $this->respuestaError($e);
        }
    }

    public function update(Request $request, $nit)
    {
        try {
            $validated = $request->validate(
                [
                    'nombre'    => 'sometimes|string|max:255',
                    'direccion' => 'sometimes|string|max:255',
                    'telefono'  => 'sometimes|string|max:11',
                    'estado'    => 'sometimes|in:Activo,Inactivo',
                ]
            );

            $empresa = $this->empresaService->actualizar($nit, $validated);
            return response()->json(['success' => true, 'data' => $empresa]);

        } catch (\Illuminate\Validation\ValidationException $e) { //controlar errores de validacion.
            return response()->json([
                'success' => false,
                'message' => 'Error de validación en los datos enviados.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) { //controla otros tipos de errores a nivel del servidor.
            return $this->respuestaError($e);
        }
    }

    public function destroy($nit)
    {
        try {
            $this->empresaService->eliminar($nit);
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Empresa eliminada correctamente'
                ], 200
            );
        } catch (ModelNotFoundException $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ], 404
            );
        } catch (Exception $e) {
            return $this->respuestaError($e);
        }
    }

    private function respuestaError(Exception $e)
    {
        return response()->json([
            'success' => false,
            'message' => 'Error interno del servidor',
            'error' => $e->getMessage()
        ], 500);
    }
}
