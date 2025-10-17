<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin - Credenciais mais seguras e fáceis de lembrar
        User::updateOrCreate([
            'email' => 'superadmin@gmail.com',
        ], [
            'name' => 'Super Administrador',
            'password' => Hash::make('superadmin123'),
            'role' => 'super_admin',
            'active' => true,
        ]);

        // Admin
        User::updateOrCreate([
            'email' => 'admin@gmail.com',
        ], [
            'name' => 'Administrador',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'active' => true,
        ]);

        // Estoquista
        User::updateOrCreate([
            'email' => 'estoquista@gmailcom',
        ], [
            'name' => 'Estoquista',
            'password' => Hash::make('estoque123'),
            'role' => 'estoquista',
            'active' => true,
        ]);

        // Caixa
        User::updateOrCreate([
            'email' => 'caixa@gmail.com',
        ], [
            'name' => 'Operador de Caixa',
            'password' => Hash::make('caixa123'),
            'role' => 'cashier',
            'active' => true,
        ]);

        // Usuário comum
        User::updateOrCreate([
            'email' => 'cliente@gmail.com',
        ], [
            'name' => 'Cliente',
            'password' => Hash::make('cliente123'),
            'role' => 'user',
            'active' => true,
        ]);
    }
}
