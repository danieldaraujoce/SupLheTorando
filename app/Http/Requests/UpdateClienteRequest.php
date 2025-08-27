<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UpdateClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'whatsapp' => preg_replace('/\D/', '', $this->whatsapp),
        ]);
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255'],
            // A regra 'unique' agora ignora o ID do cliente atual
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($this->cliente)],
            // A senha agora é opcional ('nullable')
            'senha' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'whatsapp' => ['nullable', 'string', 'min:10', 'max:11'],
            'coins' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'string', 'in:ativo,inativo'],
        ];
    }
}