<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Role::where('name', 'admin')->exists()) {
            Role::create(['name' => 'admin']);
        }
        
        if (!Role::where('name', 'user')->exists()) {
            Role::create(['name' => 'user']);
        }
        
        // Criar o administrador
        $admin = User::factory()->create([
            'login' => 'admin',
            'password' => Hash::make('Cobuccio2025!!@@'),
            'user_type' => 'admin',
            'balance' => 100.00
        ]);
        
        $admin->assignRole('admin');
        
        // Criar o usuário
        $user = User::factory()->create([
            'login' => 'user',
            'password' => Hash::make('Cobuccio2025!!@@'),
            'user_type' => 'user',
            'balance' => 50.00
        ]);

        $user->assignRole('user');


    }
}
