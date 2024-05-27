<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role1 = Role::create(['nom' => 'Super Administrateur', 'slug' => 'root']);

        $role2 = Role::create(['nom' => 'Administrateur', 'slug' => 'administrateur']);

        $role3 = Role::create(['nom' => 'Client', 'slug' => 'client']);

    }
}
