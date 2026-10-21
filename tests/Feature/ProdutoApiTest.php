<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ProdutoApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_pode_criar_um_produto(): void
    {
        $resposta = $this->postJson('/api/produtos', [
            'nome' => 'Caderno',
            'descricao' => 'Caderno universitário',
            'preco' => 19.90,
            'estoque' => 10,
        ]);

        $resposta->assertCreated()
            ->assertJsonPath('data.nome', 'Caderno');

        $this->assertDatabaseHas('produtos', ['nome' => 'Caderno']);
    }

    public function test_nao_cria_produto_sem_campos_obrigatorios(): void
    {
        $this->postJson('/api/produtos', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['nome', 'preco', 'estoque']);
    }

    public function test_consulta_clima_na_open_meteo(): void
    {
        Http::fake([
            'api.open-meteo.com/*' => Http::response([
                'latitude' => -23.55,
                'longitude' => -46.63,
                'timezone' => 'America/Sao_Paulo',
                'current' => ['temperature_2m' => 25.0],
                'current_units' => ['temperature_2m' => '°C'],
            ], 200),
        ]);

        $this->getJson('/api/clima?latitude=-23.55&longitude=-46.63')
            ->assertOk()
            ->assertJsonPath('fonte', 'Open-Meteo')
            ->assertJsonPath('clima_atual.temperature_2m', 25);
    }
}
