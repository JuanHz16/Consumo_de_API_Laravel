<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProdutoResource;
use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index()
    {
        return ProdutoResource::collection(Produto::latest()->get());
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'preco' => ['required', 'numeric', 'min:0'],
            'estoque' => ['required', 'integer', 'min:0'],
        ]);

        $produto = Produto::create($dados);

        return (new ProdutoResource($produto))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Produto $produto)
    {
        return new ProdutoResource($produto);
    }

    public function update(Request $request, Produto $produto)
    {
        $dados = $request->validate([
            'nome' => ['sometimes', 'required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'preco' => ['sometimes', 'required', 'numeric', 'min:0'],
            'estoque' => ['sometimes', 'required', 'integer', 'min:0'],
        ]);

        $produto->update($dados);

        return new ProdutoResource($produto->fresh());
    }

    public function destroy(Produto $produto)
    {
        $produto->delete();

        return response()->json([
            'mensagem' => 'Produto excluído com sucesso.',
        ]);
    }
}
