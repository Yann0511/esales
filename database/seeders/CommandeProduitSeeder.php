<?php

namespace Database\Seeders;

use App\Models\CommandeProduit;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CommandeProduitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $combinationsExistantes = [];

        for ($i = 0; $i < 10; $i++) {
            $commandeId = $faker->numberBetween(1, 9);
            $produitId = $faker->numberBetween(1, 11);
            $combinationKey = $commandeId . '-' . $produitId;

            if (!in_array($combinationKey, $combinationsExistantes)) {
                CommandeProduit::create([
                    'commandeId' => $commandeId,
                    'produitId' => $produitId,
                    'quantite' => $faker->numberBetween(1, 2),
                ]);
                $combinationsExistantes[] = $combinationKey;
            }
        }
    }
}
