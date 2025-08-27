<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfertaVip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OfertaVipController extends Controller
{
    public function index()
    {
        $dados['titulo_pagina'] = 'Ofertas VIP';
        $dados['ofertas'] = OfertaVip::orderBy('data_fim', 'desc')->paginate(10);
        return view('admin.ofertas-vip.index', $dados);
    }

    public function create()
    {
        $dados['titulo_pagina'] = 'Nova Oferta VIP';
        return view('admin.ofertas-vip.create', $dados);
    }

    public function store(Request $request)
    {
        $request->validate(['titulo' => 'required|string|max:255', /* ... (regras como as anteriores) ... */]);
        $dados = $request->except(['_token']);
        if ($request->hasFile('imagem_capa')) { $dados['imagem_capa'] = $request->file('imagem_capa')->store('public/ofertas-vip/capas'); }
        if ($request->hasFile('arquivo_url')) { $dados['arquivo_url'] = $request->file('arquivo_url')->store('public/ofertas-vip/arquivos'); }
        OfertaVip::create($dados);
        return redirect()->route('admin.ofertas-vip.index')->with('success', 'Oferta VIP criada com sucesso!');
    }

    public function edit(OfertaVip $ofertas_vip)
    {
        $dados['titulo_pagina'] = 'Editar Oferta VIP';
        $dados['oferta'] = $ofertas_vip;
        return view('admin.ofertas-vip.edit', $dados);
    }

    public function update(Request $request, OfertaVip $ofertas_vip)
    {
        $request->validate(['titulo' => 'required|string|max:255', /* ... */]);
        $dados = $request->except(['_token', '_method']);
        if ($request->hasFile('imagem_capa')) { Storage::delete($ofertas_vip->imagem_capa); $dados['imagem_capa'] = $request->file('imagem_capa')->store('public/ofertas-vip/capas'); }
        if ($request->hasFile('arquivo_url')) { Storage::delete($ofertas_vip->arquivo_url); $dados['arquivo_url'] = $request->file('arquivo_url')->store('public/ofertas-vip/arquivos'); }
        $ofertas_vip->update($dados);
        return redirect()->route('admin.ofertas-vip.index')->with('success', 'Oferta VIP atualizada com sucesso!');
    }

    public function destroy(OfertaVip $ofertas_vip)
    {
        Storage::delete($ofertas_vip->imagem_capa);
        Storage::delete($ofertas_vip->arquivo_url);
        $ofertas_vip->delete();
        return redirect()->route('admin.ofertas-vip.index')->with('success', 'Oferta VIP excluída com sucesso!');
    }
}