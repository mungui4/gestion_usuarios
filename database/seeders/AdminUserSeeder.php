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
    public function run(): void //Ingresa los datos para los usuarios con rol de aministrador a la db
    {
        //
        User::create([
            'name' => 'William',
            'last_name' => 'Munguia',
            'address' => 'Mi ciudad, mi calle #25',
            'email' => 'admin@correo1.com',
            'phone' => '2000-000',
            'password' => Hash::make('123456789'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Raúl',
            'last_name' => 'Gallardo',
            'address' => 'Mi ciudad, mi calle #26',
            'email' => 'admin@correo2.com',
            'phone' => '3000-000',
            'password' => Hash::make('123456789'),
            'role' => 'admin',
        ]);
    }
}
