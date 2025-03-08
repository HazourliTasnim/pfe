<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Entreprise;

class EntrepriseSeeder extends Seeder
{
    public function run()
    {
        Entreprise::create([
            'nom_entreprise'  => 'Test Corp',
            'activite'        => 'Informatique',
            'categorie'       => 'Technologie',
            'pays'            => 'Algérie',
            'wilaya'          => 'Alger',
            'adresse'         => 'Rue 123',
            'code_commerce'   => '123456789',
            'num_fiscal'      => '987654321',
            'num_statistique' => '123456987',
            'num_national'    => '789123456',
            'nombre_employes' => 50,
            'logo'            => 'logos/test.png'
        ]);
    }
}

