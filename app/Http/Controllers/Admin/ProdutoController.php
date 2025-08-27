<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use App\Models\CategoriaProduto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdutoController extends Controller
{
    public function index() {
        $dados['titulo_pagina'] = 'Catálogo de Produtos';
        $dados['produtos'] = Produto::with('categoria')->orderBy('nome')->paginate(12);
        return view('admin.produtos.index', $dados);
    }

    public function create() {
        $dados['titulo_pagina'] = 'Novo Produto';
        $dados['categorias'] = CategoriaProduto::orderBy('nome')->get();
        return view('admin.produtos.create', $dados);
    }

    public function store(Request $request) {
        // Remove a formatação para validação (ex: "1.250,99" -> "1250.99")
        if ($request->has('preco')) {
            $preco = str_replace('.', '', $request->preco); // remove o separador de milhar
            $preco = str_replace(',', '.', $preco); // substitui a vírgula por ponto
            $request->merge(['preco' => $preco]);
        }
        
        $request->validate([
            'nome' => 'required',
            'preco' => 'required|numeric|min:0|max:999999.99',
            'codigo_barras' => 'nullable|unique:produtos,codigo_barras'
        ]);
        
        $dados = $request->except('_token');

        if ($request->hasFile('imagem_url')) {
            $dados['imagem_url'] = $request->file('imagem_url')->store('public/produtos');
        }

        $dados['estoque'] = $dados['estoque'] ?? 0;
        $dados['status'] = $dados['status'] ?? 'ativo';

        Produto::create($dados);

        return redirect()->route('admin.produtos.index')->with('success', 'Produto criado com sucesso!');
    }

    public function show(Produto $produto)
    {
        return redirect()->route('admin.produtos.edit', $produto);
    }

    public function edit(Produto $produto) {
        $dados['titulo_pagina'] = 'Editar Produto';
        $dados['produto'] = $produto;
        $dados['categorias'] = CategoriaProduto::orderBy('nome')->get();
        return view('admin.produtos.edit', $dados);
    }

    public function update(Request $request, Produto $produto) {
        // Remove a formatação para validação (ex: "1.250,99" -> "1250.99")
        if ($request->has('preco')) {
            $preco = str_replace('.', '', $request->preco); // remove o separador de milhar
            $preco = str_replace(',', '.', $preco); // substitui a vírgula por ponto
            $request->merge(['preco' => $preco]);
        }

        $request->validate([
            'nome' => 'required',
            'preco' => 'required|numeric|min:0|max:999999.99',
            'codigo_barras' => 'nullable|unique:produtos,codigo_barras,'.$produto->id
        ]);

        $dados = $request->except(['_token', '_method']);
        if ($request->hasFile('imagem_url')) {
            if ($produto->imagem_url) {
                Storage::delete($produto->imagem_url);
            }
            $dados['imagem_url'] = $request->file('imagem_url')->store('public/produtos');
        }
        $produto->update($dados);
        return redirect()->route('admin.produtos.index')->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Produto $produto) {
        if ($produto->imagem_url) {
            Storage::delete($produto->imagem_url);
        }
        $produto->delete();
        return redirect()->route('admin.produtos.index')->with('success', 'Produto excluído com sucesso!');
    }

    public function cadastroRapido()
    {
        $dados['titulo_pagina'] = 'Cadastro Rápido de Produto';
        $dados['categorias'] = CategoriaProduto::orderBy('nome')->get();
        return view('admin.produtos.cadastro-rapido', $dados);
    }

    public function buscarProdutoPorApi($codigo_barras)
    {
        if ($codigo_barras == '7891910000197') {
            return response()->json([
                'success' => true,
                'data' => [
                    'nome' => 'Leite Integral Camponesa 1L',
                    'codigo_barras' => '7891910000197',
                    'categorias' => 'Laticínios',
                    'imagem_url' => 'https://static.paodeacucar.com/static/pa/326857-leite-integral-camponesa-1l-g.jpg',
                    'preco' => 5.99,
                    'estoque' => 10,
                ]
            ]);
        }
        
        return response()->json(['success' => false, 'message' => 'Produto não encontrado.']);
    }

    public function gerarDescricaoIA(Request $request)
    {
        $request->validate(['nome_produto' => 'required|string|max:100']);
        
        $nomeProduto = $request->input('nome_produto');
        $client = Gemini::client(env('GEMINI_API_KEY'));

        $prompt = "Crie uma descrição de marketing curta e atrativa, com no máximo 20 palavras, para um produto de supermercado chamado '{$nomeProduto}'. Foque nos benefícios e na qualidade.";

        try {
            $response = $client->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

            $descricao = $response->choices[0]->message->content;
            return response()->json(['descricao' => trim($descricao)]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Não foi possível gerar a descrição.'], 500);
        }
    }
}