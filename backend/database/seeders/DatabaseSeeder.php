<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
        ]);

        User::updateOrCreate(
            [
                'email' => 'admin@nextlvlstock.test',
            ],
            [
                'name' => 'Administrador',
                'password' => 'password',
                'is_active' => true,
                'role_id' => Role::where('name', 'Administrador')->first()->id,
            ]
        );
    }
}
