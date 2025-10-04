<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DefaultUsersSeeder extends Seeder
{
    public function run()
    {
        // Super Admin
        User::updateOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'password' => Hash::make('superadmin123'),
                'role' => 'super_admin',
                'active' => true,
            ]
        );

        // Admin
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrador',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'active' => true,
            ]
        );

        // Operador / Caixa
        User::updateOrCreate(
            ['email' => 'caixa@gmail.com'],
            [
                'name' => 'Operador de Caixa',
                'email' => 'caixa@gmail.com',
                'password' => Hash::make('caixa123'),
                'role' => 'cashier',
                'active' => true,
            ]
        );
    }
}
