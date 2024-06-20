<?php

namespace Database\Seeders;

use App\Models\Panier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PanierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Panier::create([
            'userId' => 1,

        ]);
        Panier::create([
            'userId' => 2,

        ])
        ;Panier::create([
            'userId' => 3,

        ]);
    }
}
