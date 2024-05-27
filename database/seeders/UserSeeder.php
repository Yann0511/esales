<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* root */
        $user = User::create([
            'nom' => 'OLOU',
            'prenoms' => 'Yann',
            'email' => 'yannkellyolou.eu@gmail.com',
            'password' =>  'password',
            'dateDeNaissance' => '2001-10-05',
            'telephone' => '0748278615',
            'adresse' => '22 Square Louis Armand, 35000',
            'photo'=> 'defauld.png',
            'roleId' =>  1,
            'statut' => 1,
            'emailVerifiedAt' => now()
        ]);

    }
}
