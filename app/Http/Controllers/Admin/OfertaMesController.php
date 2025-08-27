<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfertaMes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OfertaMesController extends Controller
{
    public function index()
    {
        $dados['titulo_pagina'] = 'Ofertas do Mês';
        $dados['ofertas'] = OfertaMes::orderBy('data_fim', 'desc')->paginate(10);
        return view('admin.ofertas-mes.index', $dados);
    }

    public function create()
    {
        $dados['titulo_pagina'] = 'Nova Oferta do Mês';
        return view('admin.ofertas-mes.create', $dados);
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'imagem_capa' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'arquivo_url' => 'required|file|mimes:pdf,jpeg,png,jpg|max:10240',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
        ]);

        $dados = $request->except(['_token']);
        
        if ($request->hasFile('imagem_capa')) {
            $dados['imagem_capa'] = $request->file('imagem_capa')->store('public/ofertas-mes/capas');
        }
        if ($request->hasFile('arquivo_url')) {
            $dados['arquivo_url'] = $request->file('arquivo_url')->store('public/ofertas-mes/arquivos');
        }

        OfertaMes::create($dados);

        return redirect()->route('admin.ofertas-mes.index')->with('success', 'Oferta do Mês criada com sucesso!');
    }

    // Variável renomeada de $ofertaMe para $oferta
    public function edit(OfertaMes $oferta)
    {
        $dados['titulo_pagina'] = 'Editar Oferta do Mês';
        $dados['oferta'] = $oferta;
        return view('admin.ofertas-mes.edit', $dados);
    }

    // Variável renomeada de $ofertaMe para $oferta
    public function update(Request $request, OfertaMes $oferta)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'imagem_capa' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'arquivo_url' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:10240',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
        ]);

        $dados = $request->except(['_token', '_method']);

        if ($request->hasFile('imagem_capa')) {
            Storage::delete($oferta->imagem_capa);
            $dados['imagem_capa'] = $request->file('imagem_capa')->store('public/ofertas-mes/capas');
        }
        if ($request->hasFile('arquivo_url')) {
            Storage::delete($oferta->arquivo_url);
            $dados['arquivo_url'] = $request->file('arquivo_url')->store('public/ofertas-mes/arquivos');
        }

        $oferta->update($dados);

        return redirect()->route('admin.ofertas-mes.index')->with('success', 'Oferta do Mês atualizada com sucesso!');
    }

    // Variável renomeada de $ofertaMe para $oferta
    public function destroy(OfertaMes $oferta)
    {
        Storage::delete($oferta->imagem_capa);
        Storage::delete($oferta->arquivo_url);
        $oferta->delete();
        return redirect()->route('admin.ofertas-mes.index')->with('success', 'Oferta do Mês excluída com sucesso!');
    }
}