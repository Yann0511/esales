<?php

namespace Database\Seeders;

use App\Models\Notation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class NotationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $notationsCreated = [];

        for ($i = 0; $i < 30; $i++) {
            $produitId = $faker->numberBetween(1, 10);
            $userId = $faker->numberBetween(1, 3);
            $combinationKey = $produitId . '-' . $userId;

            // Check if this combination has already been created
            if (!isset($notationsCreated[$combinationKey])) {
                Notation::create([
                    'note' => $faker->numberBetween(1, 5),
                    'produitId' => $produitId,
                    'userId' => $userId,
                ]);
                // Mark this combination as created
                $notationsCreated[$combinationKey] = true;
            }
        }
    }
}
