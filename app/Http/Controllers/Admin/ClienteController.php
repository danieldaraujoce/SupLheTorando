<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest; // Importa a nova request
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ClienteController extends Controller
{
    public function index()
    {
        $dados['titulo_pagina'] = 'Lista de Clientes';
        $dados['clientes'] = User::where('nivel_acesso', 'cliente')->paginate(10);
        return view('admin.clientes.index', $dados);
    }

    public function create()
    {
        $dados['titulo_pagina'] = 'Adicionar Novo Cliente';
        return view('admin.clientes.create', $dados);
    }

    public function store(StoreClienteRequest $request)
    {
        User::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'senha' => Hash::make($request->senha),
            'whatsapp' => $request->whatsapp,
            'coins' => $request->coins ?? 0,
            'status' => $request->status,
            'nivel_acesso' => 'cliente',
        ]);

        return redirect()->route('admin.clientes.index')
                         ->with('success', 'Cliente criado com sucesso!');
    }

    /**
     * Mostra o formulário de edição para o cliente especificado.
     */
    public function edit(User $cliente)
    {
        $dados['titulo_pagina'] = 'Editar Cliente';
        $dados['cliente'] = $cliente; // O Laravel já encontra o cliente pelo ID na URL

        return view('admin.clientes.edit', $dados);
    }

    /**
     * Atualiza o cliente especificado no banco de dados.
     */
    public function update(UpdateClienteRequest $request, User $cliente)
    {
        // Pega os dados validados da request
        $validatedData = $request->validated();

        // Se uma nova senha foi enviada, faz o hash dela
        if (!empty($validatedData['senha'])) {
            $validatedData['senha'] = Hash::make($validatedData['senha']);
        } else {
            // Se a senha estiver vazia, remove do array para não sobrescrever a senha existente
            unset($validatedData['senha']);
        }

        // Atualiza o cliente com os dados
        $cliente->update($validatedData);

        return redirect()->route('admin.clientes.index')
                         ->with('success', 'Cliente atualizado com sucesso!');
    }

    /**
     * Remove o cliente especificado do banco de dados.
     */
    public function destroy(User $cliente)
    {
        $cliente->delete();

        return redirect()->route('admin.clientes.index')
                         ->with('success', 'Cliente excluído com sucesso!');
    }
}