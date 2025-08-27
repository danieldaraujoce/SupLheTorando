<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoriaProduto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoriaProdutoController extends Controller
{
    public function index()
    {
        $dados['titulo_pagina'] = 'Categorias de Produtos';
        $dados['categorias'] = CategoriaProduto::orderBy('nome')->paginate(15);
        return view('admin.categorias-produtos.index', $dados);
    }

    public function create()
    {
        $dados['titulo_pagina'] = 'Nova Categoria';
        return view('admin.categorias-produtos.create', $dados);
    }

    public function store(Request $request)
    {
        $request->validate(['nome' => 'required|string|max:255|unique:categorias_produtos,nome']);
        
        CategoriaProduto::create([
            'nome' => $request->nome,
            'slug' => Str::slug($request->nome, '-'),
        ]);

        return redirect()->route('admin.categorias-produtos.index')->with('success', 'Categoria criada com sucesso!');
    }

    public function edit(CategoriaProduto $categorias_produto)
    {
        $dados['titulo_pagina'] = 'Editar Categoria';
        $dados['categoria'] = $categorias_produto;
        return view('admin.categorias-produtos.edit', $dados);
    }

    public function update(Request $request, CategoriaProduto $categorias_produto)
    {
        $request->validate(['nome' => 'required|string|max:255|unique:categorias_produtos,nome,' . $categorias_produto->id]);

        $categorias_produto->update([
            'nome' => $request->nome,
            'slug' => Str::slug($request->nome, '-'),
        ]);

        return redirect()->route('admin.categorias-produtos.index')->with('success', 'Categoria atualizada com sucesso!');
    }

    public function destroy(CategoriaProduto $categorias_produto)
    {
        // Adicionar lógica para verificar se a categoria tem produtos antes de excluir, se necessário.
        $categorias_produto->delete();
        return redirect()->route('admin.categorias-produtos.index')->with('success', 'Categoria excluída com sucesso!');
    }
}