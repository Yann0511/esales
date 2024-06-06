<?php

namespace Database\Seeders;

use App\Models\Commande;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
        
    }
}
