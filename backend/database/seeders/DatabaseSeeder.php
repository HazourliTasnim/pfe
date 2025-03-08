<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
     
        $this->call([
            EntrepriseSeeder::class,
            UserSeeder::class,
        ]);

        // Création du Super Admin
        User::create([
            'nom' => 'Super',
            'prenom' => 'Admin',
            'email' => 'admin@e.com',
            'password' => Hash::make('superadmin'),
            'telephone' => '05555555555',
            'role' => 'admin',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
