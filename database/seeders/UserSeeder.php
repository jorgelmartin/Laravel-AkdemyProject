<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Encriptar la contraseña usando bcrypt()
        $hashedPassword = Hash::make('Hola1234');

        DB::table('users')->insert([
            [
                'name' => 'admin',
                'surname' => 'admin',
                'email' => 'admin@admin.com',
                'password' => $hashedPassword, 
                'role_id' => '1'
            ],
            [
                'name' => 'user',
                'surname' => 'user',
                'email' => 'user@user.com',
                'password' => $hashedPassword, 
                'role_id' => '2'
            ]
        ]);
    }
}