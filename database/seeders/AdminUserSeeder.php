<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usa firstOrCreate para evitar criar o usuário duplicado se o seeder for rodado novamente.
        User::firstOrCreate(
            [
                'email' => 'admin@example.com' // O e-mail que será usado para o login
            ],
            [
                'nome' => 'Administrador',
                'senha' => Hash::make('password'), // A senha será 'password'
                'nivel_acesso' => 'admin',
                'email_verified_at' => now(), // Já marca o e-mail como verificado
            ]
        );
    }
}