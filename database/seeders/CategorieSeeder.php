<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Seeder;


class CategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Définition des données de catégorie
        $categories = Categorie::create([  
        'nom' => 'Electronics', 'icone' => 'icon-electronics.png',
       
        // Ajoutez d'autres catégories ici si nécessaire
        ]);
        $categories = Categorie::create([  
            'nom' => 'Vetement', 'icone' => 'icon-vetement.png',
        ]);
        $categories = Categorie::create([  
            'nom' => 'Meuble', 'icone' => 'icon-meuble.png',
        ]);


       
    }
}
