<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::updateOrCreate([
            'name' => 'Administrador',
            'is_active' => true,
        ]);

        Role::updateOrCreate([
            'name' => 'Supervisor',
            'is_active' => true,
        ]);

        Role::updateOrCreate([
            'name' => 'Comprador',
            'is_active' => true,
        ]);

        Role::updateOrCreate([
            'name' => 'Bodeguero',
            'is_active' => true,
        ]);

        Role::updateOrCreate([
            'name' => 'Vendedor',
            'is_active' => true,
        ]);
    }
}
