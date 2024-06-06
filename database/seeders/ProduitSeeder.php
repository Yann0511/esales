<?php

namespace Database\Seeders;

use App\Models\Produit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProduitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Produit::create([
            'nom' => "telephone",
            'description' => "telephone intelligent",
            'prix' => 143,
            'qte' => 14,
            'categorieId' => 1,

        ]);
    }
}
