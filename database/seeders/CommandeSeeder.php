<?php

namespace Database\Seeders;

use App\Models\Commande;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class CommandeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            Commande::create([
                'adresse' =>"123 rue exemple",
                'numero' => "07 63 57 80 32",
                'montant' => 73.2,
                'statut' => 1, 
                'auteurId' => 1, 
                'livreurId' =>1, 
            ]);
            Commande::create([
                'adresse' =>"123 rue exemple 2",
                'numero' => "07 63 57 80 52",
                'montant' => 135.2,
                'statut' => 1, 
                'auteurId' => 1, 
                'livreurId' =>1, 
            ]);
            $faker = Faker::create();

        for ($i = 0; $i < 7; $i++) {
            Commande::create([
                'adresse' => $faker->address,
                'numero' => $faker->phoneNumber,
                'montant' => $faker->randomFloat(2, 20, 500),
                'statut' => 1,
                'auteurId' => $faker->numberBetween(1, 3),
                'livreurId' => $faker->numberBetween(1, 3), 
            ]);
        }
            
        
    }
}
