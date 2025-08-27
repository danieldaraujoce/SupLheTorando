<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuiaDica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuiaDicaController extends Controller
{
    public function index()
    {
        $dados['titulo_pagina'] = 'Guias e Dicas';
        $dados['guias'] = GuiaDica::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.guias-dicas.index', $dados);
    }

    public function create()
    {
        $dados['titulo_pagina'] = 'Nova Guia ou Dica';
        return view('admin.guias-dicas.create', $dados);
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'imagem_capa' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'arquivo_url' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
        ]);

        $dados = $request->except(['_token']);

        if ($request->hasFile('imagem_capa')) {
            $dados['imagem_capa'] = $request->file('imagem_capa')->store('public/guias-dicas/capas');
        }
        if ($request->hasFile('arquivo_url')) {
            $dados['arquivo_url'] = $request->file('arquivo_url')->store('public/guias-dicas/arquivos');
        }

        GuiaDica::create($dados);

        return redirect()->route('admin.guias-dicas.index')->with('success', 'Guia/Dica criada com sucesso!');
    }

    public function edit(GuiaDica $guias_dica)
    {
        $dados['titulo_pagina'] = 'Editar Guia ou Dica';
        $dados['guia'] = $guias_dica;
        return view('admin.guias-dicas.edit', $dados);
    }

    public function update(Request $request, GuiaDica $guias_dica)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'imagem_capa' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'arquivo_url' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
        ]);

        $dados = $request->except(['_token', '_method']);

        if ($request->hasFile('imagem_capa')) {
            Storage::delete($guias_dica->imagem_capa);
            $dados['imagem_capa'] = $request->file('imagem_capa')->store('public/guias-dicas/capas');
        }
        if ($request->hasFile('arquivo_url')) {
            Storage::delete($guias_dica->arquivo_url);
            $dados['arquivo_url'] = $request->file('arquivo_url')->store('public/guias-dicas/arquivos');
        }

        $guias_dica->update($dados);

        return redirect()->route('admin.guias-dicas.index')->with('success', 'Guia/Dica atualizada com sucesso!');
    }

    public function destroy(GuiaDica $guias_dica)
    {
        Storage::delete($guias_dica->imagem_capa);
        Storage::delete($guias_dica->arquivo_url);
        $guias_dica->delete();
        return redirect()->route('admin.guias-dicas.index')->with('success', 'Guia/Dica excluída com sucesso!');
    }
}