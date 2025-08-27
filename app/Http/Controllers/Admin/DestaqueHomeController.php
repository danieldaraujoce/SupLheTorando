<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DestaqueHome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // Importa a facade File

class DestaqueHomeController extends Controller
{
    public function index()
    {
        $dados['titulo_pagina'] = 'Destaques da Home';
        $dados['destaques'] = DestaqueHome::orderBy('ordem')->get();
        return view('admin.destaques-home.index', $dados);
    }

    public function create()
    {
        $dados['titulo_pagina'] = 'Novo Destaque';
        return view('admin.destaques-home.create', $dados);
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'imagem' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'texto_botao' => 'required|string|max:50',
            'link_botao' => 'required|string|max:255',
            'ordem' => 'nullable|integer',
            'valor_moedas' => 'nullable|integer',
            'subtitulo' => 'nullable|string|max:255',
            'status' => 'required|in:ativo,inativo', // <-- CORREÇÃO: Adicionada validação para o status
        ]);

        $dados = $request->except('_token');

        if ($request->hasFile('imagem')) {
            // Move o arquivo para public/uploads/destaques e salva o caminho
            $imageName = time().'.'.$request->imagem->extension();
            $request->imagem->move(public_path('uploads/destaques'), $imageName);
            $dados['imagem'] = 'uploads/destaques/'.$imageName;
        }

        DestaqueHome::create($dados);

        return redirect()->route('admin.destaques-home.index')->with('success', 'Destaque criado com sucesso!');
    }

    public function edit(DestaqueHome $destaque)
    {
        $dados['titulo_pagina'] = 'Editar Destaque';
        $dados['destaque'] = $destaque;
        return view('admin.destaques-home.edit', $dados);
    }

    public function update(Request $request, DestaqueHome $destaque)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048', // Nullable na atualização
            'texto_botao' => 'required|string|max:50',
            'link_botao' => 'required|string|max:255',
            'ordem' => 'nullable|integer',
            'valor_moedas' => 'nullable|integer',
            'subtitulo' => 'nullable|string|max:255',
            'status' => 'required|in:ativo,inativo', // <-- CORREÇÃO: Adicionada validação para o status
        ]);
        
        $dados = $request->except(['_token', '_method']);

        if ($request->hasFile('imagem')) {
            // Deleta a imagem antiga (seja da pasta public ou storage)
            if ($destaque->imagem) {
                if (str_starts_with($destaque->imagem, 'uploads/') && File::exists(public_path($destaque->imagem))) {
                    File::delete(public_path($destaque->imagem));
                } elseif (\Illuminate\Support\Facades\Storage::exists($destaque->imagem)) {
                    \Illuminate\Support\Facades\Storage::delete($destaque->imagem);
                }
            }
            
            // Move a nova imagem para public/uploads/destaques
            $imageName = time().'.'.$request->imagem->extension();
            $request->imagem->move(public_path('uploads/destaques'), $imageName);
            $dados['imagem'] = 'uploads/destaques/'.$imageName;
        }

        $destaque->update($dados);

        return redirect()->route('admin.destaques-home.index')->with('success', 'Destaque atualizado com sucesso!');
    }

    public function destroy(DestaqueHome $destaque)
    {
        // Deleta a imagem associada (seja da pasta public ou storage)
        if ($destaque->imagem) {
            if (str_starts_with($destaque->imagem, 'uploads/') && File::exists(public_path($destaque->imagem))) {
                File::delete(public_path($destaque->imagem));
            } elseif (\Illuminate\Support\Facades\Storage::exists($destaque->imagem)) {
                \Illuminate\Support\Facades\Storage::delete($destaque->imagem);
            }
        }
        
        $destaque->delete();

        return redirect()->route('admin.destaques-home.index')->with('success', 'Destaque excluído com sucesso!');
    }
}