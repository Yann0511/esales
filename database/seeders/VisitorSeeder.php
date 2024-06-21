<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Visitor;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class VisitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $visitors = [];

        for ($i = 0; $i < 40; $i++) {
            $createdAt = $faker->dateTimeBetween('-3 years', 'now');
            $updatedAt = $faker->dateTimeBetween($createdAt, 'now');

            $visitors[] = [
                'ip_address' => $faker->ipv4,
                'visited_at' => $updatedAt,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ];
        }

        Visitor::insert($visitors);
    }
}
