<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class StoreClienteRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer esta requisição.
     */
    public function authorize(): bool
    {
        // Altere para true para permitir que a requisição prossiga.
        return true;
    }

    /**
     * Prepara os dados para validação.
     */
    protected function prepareForValidation(): void
    {
        // Limpa o campo whatsapp, removendo tudo que não for número
        // antes de passar para as regras de validação.
        $this->merge([
            'whatsapp' => preg_replace('/\D/', '', $this->whatsapp),
        ]);
    }

    /**
     * Define as regras de validação que se aplicam à requisição.
     */
    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'senha' => ['required', 'confirmed', Rules\Password::defaults()],
            // Agora validamos o número limpo (apenas dígitos)
            'whatsapp' => ['nullable', 'string', 'min:10', 'max:11'],
            'coins' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'string', 'in:ativo,inativo'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }
}