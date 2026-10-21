<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ClimaController extends Controller
{
    public function consultar(Request $request)
    {
        $dados = $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $resposta = Http::timeout(10)
            ->get('https://api.open-meteo.com/v1/forecast', [
                'latitude' => $dados['latitude'],
                'longitude' => $dados['longitude'],
                'current' => 'temperature_2m,relative_humidity_2m,wind_speed_10m',
                'timezone' => 'auto',
            ]);

        if ($resposta->failed()) {
            return response()->json([
                'mensagem' => 'Não foi possível consultar o serviço de clima.',
            ], 502);
        }

        $clima = $resposta->json();

        return response()->json([
            'fonte' => 'Open-Meteo',
            'localizacao' => [
                'latitude' => $clima['latitude'],
                'longitude' => $clima['longitude'],
                'fuso_horario' => $clima['timezone'],
            ],
            'clima_atual' => $clima['current'] ?? [],
            'unidades' => $clima['current_units'] ?? [],
        ]);
    }
}
