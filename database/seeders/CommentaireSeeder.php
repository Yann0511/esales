<?php

namespace Database\Seeders;

use App\Models\Commentaire;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class CommentaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $commentairesCreated = [];

        for ($i = 0; $i < 30; $i++) {
            $produitId = $faker->numberBetween(1, 10);
            $userId = $faker->numberBetween(1, 3);
            $combinationKey = $produitId . '-' . $userId;

            // Check if this combination has already been created
            if (!isset($commentairesCreated[$combinationKey])) {
                Commentaire::create([
                    'contenu' => $faker->sentence(),
                    'produitId' => $produitId,
                    'userId' => $userId,
                ]);
                // Mark this combination as created
                $commentairesCreated[$combinationKey] = true;
            }
        }
    }
}
