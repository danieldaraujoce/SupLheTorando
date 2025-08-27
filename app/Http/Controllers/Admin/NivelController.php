<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nivel;
use App\Models\Setting;
use Illuminate\Http\Request;

class NivelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dados['titulo_pagina'] = 'Níveis de Fidelidade';
        $dados['niveis'] = Nivel::orderBy('requisito_minimo_coins')->paginate(10);
        return view('admin.niveis.index', $dados);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dados['titulo_pagina'] = 'Novo Nível';
        // Busca a taxa de conversão do banco, com um valor padrão de 10 se não encontrar
        $setting = Setting::where('key', 'real_para_coin_rate')->first();
        $dados['taxa_conversao'] = $setting ? (float) $setting->value : 10;
        return view('admin.niveis.create', $dados);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:niveis',
            'requisito_minimo_coins' => 'required|integer|min:0|unique:niveis',
            'imagem_emblema' => 'nullable|image|max:1024',
        ]);

        $dados = $request->except('imagem_emblema');

        if ($request->hasFile('imagem_emblema')) {
            $dados['imagem_emblema'] = $request->file('imagem_emblema')->store('emblemas_niveis', 'public');
        }

        Nivel::create($dados);

        return redirect()->route('admin.niveis.index')->with('success', 'Nível criado com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Nivel $nivei)
    {
        $dados['titulo_pagina'] = 'Editar Nível';
        $dados['nivel'] = $nivei;
        // Busca a taxa de conversão do banco, com um valor padrão de 10 se não encontrar
        $setting = Setting::where('key', 'real_para_coin_rate')->first();
        $dados['taxa_conversao'] = $setting ? (float) $setting->value : 10;
        return view('admin.niveis.edit', $dados);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Nivel $nivei)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:niveis,nome,' . $nivei->id,
            'requisito_minimo_coins' => 'required|integer|min:0|unique:niveis,requisito_minimo_coins,' . $nivei->id,
            'imagem_emblema' => 'nullable|image|max:1024',
        ]);
        
        $dados = $request->except('imagem_emblema');

        if ($request->hasFile('imagem_emblema')) {
            // Lógica para apagar a imagem antiga se existir
            // Storage::disk('public')->delete($nivei->imagem_emblema);
            $dados['imagem_emblema'] = $request->file('imagem_emblema')->store('emblemas_niveis', 'public');
        }

        $nivei->update($dados);

        return redirect()->route('admin.niveis.index')->with('success', 'Nível atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Nivel $nivei)
    {
        // Lógica para apagar a imagem do storage
        // Storage::disk('public')->delete($nivei->imagem_emblema);
        $nivei->delete();
        return redirect()->route('admin.niveis.index')->with('success', 'Nível excluído com sucesso!');
    }
}