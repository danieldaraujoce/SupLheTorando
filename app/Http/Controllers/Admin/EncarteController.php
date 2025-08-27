<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Encarte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EncarteController extends Controller
{
    public function index()
    {
        $dados['titulo_pagina'] = 'Encartes Promocionais';
        $dados['encartes'] = Encarte::orderBy('data_fim', 'desc')->paginate(10);
        return view('admin.encartes.index', $dados);
    }

    public function create()
    {
        $dados['titulo_pagina'] = 'Novo Encarte';
        return view('admin.encartes.create', $dados);
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'imagem_capa' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'arquivo_url' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB max
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
        ]);

        $dados = $request->except(['_token']);

        // Salva a imagem de capa
        if ($request->hasFile('imagem_capa') && $request->file('imagem_capa')->isValid()) {
            $dados['imagem_capa'] = $request->file('imagem_capa')->store('public/encartes/capas');
        }

        // Salva o arquivo principal (PDF/Imagem)
        if ($request->hasFile('arquivo_url') && $request->file('arquivo_url')->isValid()) {
            $dados['arquivo_url'] = $request->file('arquivo_url')->store('public/encartes/arquivos');
        }

        Encarte::create($dados);

        return redirect()->route('admin.encartes.index')->with('success', 'Encarte criado com sucesso!');
    }

    public function edit(Encarte $encarte)
    {
        $dados['titulo_pagina'] = 'Editar Encarte';
        $dados['encarte'] = $encarte;
        return view('admin.encartes.edit', $dados);
    }

    public function update(Request $request, Encarte $encarte)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'imagem_capa' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'arquivo_url' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
        ]);

        $dados = $request->except(['_token', '_method']);

        // Se uma nova imagem de capa foi enviada, apaga a antiga e salva a nova
        if ($request->hasFile('imagem_capa') && $request->file('imagem_capa')->isValid()) {
            Storage::delete($encarte->imagem_capa);
            $dados['imagem_capa'] = $request->file('imagem_capa')->store('public/encartes/capas');
        }

        // Se um novo arquivo principal foi enviado, apaga o antigo e salva o novo
        if ($request->hasFile('arquivo_url') && $request->file('arquivo_url')->isValid()) {
            Storage::delete($encarte->arquivo_url);
            $dados['arquivo_url'] = $request->file('arquivo_url')->store('public/encartes/arquivos');
        }

        $encarte->update($dados);

        return redirect()->route('admin.encartes.index')->with('success', 'Encarte atualizado com sucesso!');
    }

    public function destroy(Encarte $encarte)
    {
        // Apaga os arquivos do armazenamento antes de apagar o registro no banco
        Storage::delete($encarte->imagem_capa);
        Storage::delete($encarte->arquivo_url);

        $encarte->delete();

        return redirect()->route('admin.encartes.index')->with('success', 'Encarte excluído com sucesso!');
    }
}