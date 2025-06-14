<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create Permissions
        $permissions = [
            // User Management
            ['name' => 'view-users', 'display_name' => 'View Users'],
            ['name' => 'create-user', 'display_name' => 'Create User'],
            ['name' => 'edit-user', 'display_name' => 'Edit User'],
            ['name' => 'delete-user', 'display_name' => 'Delete User'],
            
            // Role Management
            ['name' => 'view-roles', 'display_name' => 'View Roles'],
            ['name' => 'create-role', 'display_name' => 'Create Role'],
            ['name' => 'edit-role', 'display_name' => 'Edit Role'],
            ['name' => 'delete-role', 'display_name' => 'Delete Role'],
            
            // Product Management
            ['name' => 'view-products', 'display_name' => 'View Products'],
            ['name' => 'create-product', 'display_name' => 'Create Product'],
            ['name' => 'edit-product', 'display_name' => 'Edit Product'],
            ['name' => 'delete-product', 'display_name' => 'Delete Product'],
            
            // Order Management
            ['name' => 'view-orders', 'display_name' => 'View Orders'],
            ['name' => 'create-order', 'display_name' => 'Create Order'],
            ['name' => 'edit-order', 'display_name' => 'Edit Order'],
            ['name' => 'delete-order', 'display_name' => 'Delete Order'],
            
            // Report Management
            ['name' => 'view-reports', 'display_name' => 'View Reports'],
            ['name' => 'generate-report', 'display_name' => 'Generate Report'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Create Roles
        $roles = [
            [
                'name' => 'owner',
                'display_name' => 'Owner',
                'description' => 'Owner of the flower shop',
                'permissions' => Permission::all()->pluck('name')->toArray() // All permissions
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Shop administrator',
                'permissions' => [
                    'view-users', 'create-user', 'edit-user',
                    'view-roles',
                    'view-products', 'create-product', 'edit-product',
                    'view-orders', 'create-order', 'edit-order',
                    'view-reports', 'generate-report'
                ]
            ],
            [
                'name' => 'kasir',
                'display_name' => 'Kasir',
                'description' => 'Shop cashier',
                'permissions' => [
                    'view-products',
                    'view-orders', 'create-order', 'edit-order',
                    'view-reports'
                ]
            ],
            [
                'name' => 'karyawan',
                'display_name' => 'Karyawan',
                'description' => 'Shop staff',
                'permissions' => [
                    'view-products',
                    'view-orders', 'create-order',
                ]
            ],
            [
                'name' => 'pelanggan',
                'display_name' => 'Pelanggan',
                'description' => 'Customer',
                'permissions' => [
                    'view-products',
                    'view-orders', 'create-order',
                ]
            ],
        ];

        foreach ($roles as $roleData) {
            $permissions = $roleData['permissions'];
            unset($roleData['permissions']);
            
            $role = Role::create($roleData);
            $role->permissions()->attach(
                Permission::whereIn('name', $permissions)->get()
            );
        }
    }
}
