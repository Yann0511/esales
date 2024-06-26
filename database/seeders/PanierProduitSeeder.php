<?php

namespace Database\Seeders;

use App\Models\PanierProduit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class PanierProduitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $combinationsExistantes = [];

        for ($i = 0; $i < 30; $i++) {
            $panierId = $faker->numberBetween(1, 3);
            $produitId = $faker->numberBetween(1, 10);
            $combinationKey = $panierId . '-' . $produitId;

            if (!in_array($combinationKey, $combinationsExistantes)) {
                PanierProduit::create([
                    'panierId' => $panierId,
                    'produitId' => $produitId,
                    'quantite' => $faker->numberBetween(1, 2),
                ]);
                $combinationsExistantes[] = $combinationKey;
            }
        }
    }
}
