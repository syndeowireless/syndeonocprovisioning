<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Criar usuário admin padrão
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@sistema.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Criar usuário comum para teste
        User::create([
            'name' => 'Usuário Teste',
            'email' => 'user@sistema.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
        ]);
    }
}
