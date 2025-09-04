<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Empresa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class EmpresaTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function crear_una_empresa()
    {
        $response = $this->postJson('/api/empresas', [
            'nit' => '123456789',
            'nombre' => 'Veterinaria Ikigai',
            'direccion' => 'Calle 123',
            'telefono' => '3101234567',
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'data' => ['nombre' => 'Veterinaria Ikigai']
                 ]);

        $this->assertDatabaseHas('empresas', [
            'nit' => '123456789',
            'nombre' => 'Veterinaria Ikigai',
        ]);
    }

    #[Test]
    public function no_puede_crear_empresa_con_nit_duplicado()
    {
        Empresa::factory()->create(['nit' => '123456789']);

        $response = $this->postJson('/api/empresas', [
            'nit' => '123456789',
            'nombre' => 'Duplicada',
            'direccion' => 'Carrera 99',
            'telefono' => '3200000000',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['nit']);
    }

    #[Test]
    public function listar_empresas()
    {
        Empresa::factory()->count(2)->create();

        $response = $this->getJson('/api/empresas');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         '*' => ['id', 'nit', 'nombre', 'direccion', 'telefono', 'estado']
                     ]
                 ]);
    }

    #[Test]
    public function consultar_empresa_por_nit()
    {
        $empresa = Empresa::factory()->create([
            'nit' => '987654321',
            'nombre' => 'Empresa Test'
        ]);

        $response = $this->getJson('/api/empresas/' . $empresa->nit);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => ['nombre' => 'Empresa Test']
                 ]);
    }

    #[Test]
    public function regresa_404_si_no_encuentra_empresa()
    {
        $response = $this->getJson('/api/empresas/999999999');

        $response->assertStatus(404);
    }

    #[Test]
    public function actualizar_empresa()
    {
        $empresa = Empresa::factory()->create([
            'nit' => '111111111',
            'nombre' => 'Viejo Nombre'
        ]);

        $response = $this->putJson('/api/empresas/' . $empresa->nit, [
            'nombre' => 'Nuevo Nombre'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => ['nombre' => 'Nuevo Nombre']
                 ]);

        $this->assertDatabaseHas('empresas', [
            'nit' => '111111111',
            'nombre' => 'Nuevo Nombre'
        ]);
    }

    #[Test]
    public function solo_eliminar_empresa_si_esta_inactiva()
    {
        $empresa = Empresa::factory()->create([
            'nit' => '222222222',
            'estado' => 'Inactivo'
        ]);

        $response = $this->deleteJson('/api/empresas/' . $empresa->nit);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Empresa eliminada correctamente'
                 ]);

        $this->assertDatabaseMissing('empresas', ['nit' => '222222222']);
    }

    #[Test]
    public function no_eliminar_empresa_si_esta_activa()
    {
        $empresa = Empresa::factory()->create([
            'nit' => '333333333',
            'estado' => 'Activo'
        ]);

        $response = $this->deleteJson('/api/empresas/' . $empresa->nit);

        $response->assertStatus(500)
                ->assertJson([
                    'success' => false,
                    'message' => 'Error interno del servidor',
                ])
                ->assertJsonFragment([
                    'error' => 'Solo se pueden eliminar empresas inactivas'
                ]);

        $this->assertDatabaseHas('empresas', ['nit' => '333333333']);
    }

}
