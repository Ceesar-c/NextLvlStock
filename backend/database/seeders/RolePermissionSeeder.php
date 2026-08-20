<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::where('name', 'Administrador')->first();
        $supervisor = Role::where('name', 'Supervisor')->first();
        $buyer = Role::where('name', 'Comprador')->first();
        $warehouse = Role::where('name', 'Bodeguero')->first();
        $seller = Role::where('name', 'Vendedor')->first();

        $permissions = Permission::where('is_active', true)
            ->pluck('id', 'name');

        $admin->permissions()->sync($permissions->values());

        $supervisor->permissions()->sync([
            $permissions['products.view'],
            $permissions['purchases.view'],
            $permissions['sales.view'],
            $permissions['customers.view'],
            $permissions['suppliers.view'],
            $permissions['categories.view'],
            $permissions['brands.view'],
            $permissions['users.view'],
            $permissions['roles.view'],
            $permissions['permissions.view'],
        ]);

        $buyer->permissions()->sync([
            $permissions['products.view'],
            $permissions['purchases.view'],
            $permissions['purchases.create'],
            $permissions['purchases.update'],
            $permissions['suppliers.view'],
            $permissions['suppliers.create'],
            $permissions['suppliers.update'],
            $permissions['categories.view'],
            $permissions['brands.view'],
        ]);

        $warehouse->permissions()->sync([
            $permissions['products.view'],
            $permissions['products.update'],
            $permissions['products.deactivate'],
            $permissions['products.activate'],
            $permissions['suppliers.view'],
            $permissions['categories.view'],
            $permissions['brands.view'],
        ]);

        $seller->permissions()->sync([
            $permissions['products.view'],
            $permissions['customers.view'],
            $permissions['customers.create'],
            $permissions['customers.update'],
            $permissions['sales.view'],
            $permissions['sales.create'],
            $permissions['sales.update'],
        ]);
    }
}
