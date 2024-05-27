<?php

namespace Database\Seeders;
use Faker\Factory as Faker;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $actions = [
            'Creer',
            'Modifier',
            'Supprimer',
            'Voir'
        ];
        $modules = [
            'produit',
            'role',
            'user',
        ];

        $autres = [
        ];

        /*foreach($actions as $action){
            foreach($modules as $module){

                $nom = $action.' '.$module;

                $slug = str_replace(' ', '.', strtolower($nom));

                DB::table('permissions')->insert([
                    [
                        'nom' => $nom,

                        'slug' =>$slug,

                    ]

                ]);
            }
        }

        foreach($autres as $autre){
            DB::table('permissions')->insert([
                [
                    'nom' => ucfirst(str_replace(' ', '.', $autre)),

                    'slug' =>str_replace(' ', '.', $autre)

                ]

            ]);
        }*/
    }
}
