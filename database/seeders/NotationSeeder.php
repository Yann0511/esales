<?php

namespace Database\Seeders;

use App\Models\Notation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Notation::create([
            'note' => "2",
            'produitId' => 1,
            'userId' => 1,

        ]);
        Notation::create([
            'note' => "4",
            'produitId' => 1,
            'userId' => 1,
        ]);
    }
}
