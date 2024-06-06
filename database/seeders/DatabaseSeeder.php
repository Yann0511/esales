<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(PermissionRoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CategorieSeeder::class);
        $this->call(CommandeSeeder::class);
        $this->call(PanierSeeder::class);
        $this->call(ProduitSeeder::class);
        $this->call(NotationSeeder::class);
        $this->call(PanierProduitSeeder::class);
    }
}
