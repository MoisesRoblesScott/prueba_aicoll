<?php

namespace App\Services;

use App\Models\Empresa;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class EmpresaService
{
    public function listar()
    {
        return Empresa::all();
    }

    public function consultarPorNit($nit)
    {
        $empresa = Empresa::where('nit', $nit)->first();
        if (!$empresa) {
            throw new ModelNotFoundException("Empresa con NIT {$nit} no encontrada");
        }
        return $empresa;
    }

    public function crear(array $data)
    {
        return DB::transaction(function () use ($data) {
            return Empresa::create($data);
        });
    }

    public function actualizar($nit, array $data)
    {
        return DB::transaction(function () use ($nit, $data) {
            $empresa = $this->consultarPorNit($nit);
            $empresa->update($data);
            return $empresa;
        });
    }

    public function eliminar($nit)
    {
        return DB::transaction(function () use ($nit) {
            $empresa = $this->consultarPorNit($nit);

            if ($empresa->estado !== 'Inactivo') {
                throw new Exception("Solo se pueden eliminar empresas inactivas");
            }

            $empresa->delete();
            return true;
        });
    }
}
