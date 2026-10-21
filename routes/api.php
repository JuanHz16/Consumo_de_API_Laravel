<?php

use App\Http\Controllers\Api\ClimaController;
use App\Http\Controllers\Api\ProdutoController;
use Illuminate\Support\Facades\Route;

Route::apiResource('produtos', ProdutoController::class);

Route::get('/clima', [ClimaController::class, 'consultar']);
