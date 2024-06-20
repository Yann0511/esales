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
            'telephone' => '0748278615',
            'adresse' => '22 Square Louis Armand, 35000',
            'photo'=> 'defauld.png',
            'roleId' =>  1,
            'statut' => 1,
            'emailVerifiedAt' => now()
        ]);
        $user2 = User::create([
            'nom' => 'MAHAMOUD',
            'prenoms' => 'Anrifidine',
            'email' => 'Anrifidine.mahamoud@gmail.com',
            'password' =>  'password',
            'telephone' => '0748638203',
            'adresse' => '12 av. des preales, 35700',
            'photo'=> 'defauld.png',
            'roleId' =>  2,
            'statut' => 1,
            'emailVerifiedAt' => now()
        ]);
        $user3 = User::create([
            'nom' => 'Lechevalier',
            'prenoms' => 'Lucas',
            'email' => 'Llechevalier.eu@gmail.com',
            'password' =>  'password',
            'telephone' => '0744392847',
            'adresse' => '84 bd de vitre, 35700',
            'photo'=> 'defauld.png',
            'roleId' =>  3,
            'statut' => 1,
            'emailVerifiedAt' => now()
        ]);

    }
}
