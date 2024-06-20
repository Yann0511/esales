<?php

namespace Database\Seeders;

use App\Models\Produit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

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
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            Produit::create([
                'nom' => $faker->word,
                'description' => $faker->sentence,
                'prix' => $faker->numberBetween(100, 1000),
                'qte' => $faker->numberBetween(1, 100),
                'categorieId' => $faker->numberBetween(1, 3),
            ]);
        }
    }
}
