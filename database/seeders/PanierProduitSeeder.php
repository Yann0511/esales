<?php

namespace Database\Seeders;

use App\Models\PanierProduit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PanierProduitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PanierProduit::create([
            'panierId' => 1,
            'produitId' => 1,
            'quantite' =>1,

        ]);
    }
}
