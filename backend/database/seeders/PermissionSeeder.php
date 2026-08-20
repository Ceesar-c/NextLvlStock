<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Products
            'products.view',
            'products.create',
            'products.update',
            'products.deactivate',
            'products.activate',

            // Purchases
            'purchases.view',
            'purchases.create',
            'purchases.update',

            // Sales
            'sales.view',
            'sales.create',
            'sales.update',

            // Customers
            'customers.view',
            'customers.create',
            'customers.update',

            // Suppliers
            'suppliers.view',
            'suppliers.create',
            'suppliers.update',

            // Categories
            'categories.view',
            'categories.create',
            'categories.update',

            // Brands
            'brands.view',
            'brands.create',
            'brands.update',

            // Users
            'users.view',
            'users.create',
            'users.update',
            'users.change_role',
            'users.deactivate',
            'users.activate',

            // Roles
            'roles.view',
            'roles.create',
            'roles.update',

            // Permissions
            'permissions.view',
            'permissions.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate([
                'name' => $permission,
                'is_active' => true,
            ]);
        }
    }
}
