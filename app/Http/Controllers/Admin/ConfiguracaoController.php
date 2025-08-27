<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfiguracaoController extends Controller
{
    public function index()
    {
        $dados['titulo_pagina'] = 'Configurações Gerais';
        // Pega todas as configurações e transforma em um array associativo
        $dados['settings'] = Setting::pluck('value', 'key')->all();
        return view('admin.configuracoes.index', $dados);
    }

    public function store(Request $request)
    {
        // Pega todos os campos, exceto o token e os arquivos
        $textInputs = $request->except(['_token', 'logo_marca', 'og_image', 'site_favicon']);

        // Loop para salvar/atualizar cada configuração de texto
        foreach ($textInputs as $key => $value) {
            // Garante que valores nulos não sejam salvos, caso um campo seja opcional
            if ($value !== null) {
                Setting::updateOrCreate(['key' => $key], ['value' => $value]);
            }
        }

        // CORREÇÃO: Lógica unificada para upload de arquivos de configuração
        $fileInputs = ['logo_marca', 'og_image', 'site_favicon'];

        foreach ($fileInputs as $key) {
            if ($request->hasFile($key)) {
                // 1. Encontra a configuração antiga para pegar o caminho do arquivo
                $oldSetting = Setting::where('key', $key)->first();

                // 2. Se um arquivo antigo existir, deleta do storage
                if ($oldSetting && $oldSetting->value) {
                    // O caminho salvo no banco já inclui 'public/', então não é necessário adicionar
                    Storage::delete($oldSetting->value);
                }

                // 3. Salva o novo arquivo
                // A pasta 'settings' é mais genérica e adequada para todos os arquivos
                $path = $request->file($key)->store('public/settings');

                // 4. Atualiza ou cria o registro no banco com o novo caminho do arquivo
                Setting::updateOrCreate(['key' => $key], ['value' => str_replace('public/', '', $path)]);
            }
        }

        return back()->with('success', 'Configurações salvas com sucesso!');
    }
}