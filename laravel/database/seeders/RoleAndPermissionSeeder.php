<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Exception;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        try {
            // Disable foreign key checks
            Schema::disableForeignKeyConstraints();

            // Clear existing data
            DB::table('permission_role')->truncate();
            DB::table('permission_user')->truncate();
            DB::table('role_user')->truncate();
            DB::table('permissions')->truncate();
            DB::table('roles')->truncate();

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
                
                // Settings Management
                ['name' => 'manage-settings', 'display_name' => 'Manage Settings'],
            ];

            // Insert permissions
            foreach ($permissions as $permission) {
                DB::table('permissions')->updateOrInsert(
                    ['name' => $permission['name']],
                    [
                        'display_name' => $permission['display_name'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
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
                    'description' => 'Staff Karyawan',
                    'permissions' => [
                        'view-products', 'edit-product',
                        'view-orders'
                    ]
                ],
                [
                    'name' => 'customers service',
                    'display_name' => 'Customers Service',
                    'description' => 'Customer Service',
                    'permissions' => [
                        'view-orders', 'create-order', 'edit-order',
                        'view-reports',
                        'view-users' // jika perlu akses data pelanggan
                    ]
                ]
            ];

            // Insert roles
            foreach ($roles as $role) {
                DB::table('roles')->updateOrInsert(
                    ['name' => $role['name']],
                    [
                        'display_name' => $role['display_name'],
                        'description' => $role['description'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }

            // Assign permissions to roles
            foreach ($roles as $roleData) {
                $role = Role::where('name', $roleData['name'])->first();
                $permissions = Permission::whereIn('name', $roleData['permissions'])->get();
                
                foreach ($permissions as $permission) {
                    // Insert into pivot table without timestamps
                    DB::table('permission_role')->insertOrIgnore([
                        'permission_id' => $permission->id,
                        'role_id' => $role->id
                    ]);
                }
            }

            // Create default owner user if not exists
            $owner = DB::table('users')->updateOrInsert(
                ['email' => 'owner@fellieforist.com'],
                [
                    'name' => 'Owner',
                    'password' => bcrypt('owner123'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // Assign owner role to default user
            $ownerUser = DB::table('users')->where('email', 'owner@fellieforist.com')->first();
            $ownerRole = Role::where('name', 'owner')->first();
            
            DB::table('role_user')->insertOrIgnore([
                'role_id' => $ownerRole->id,
                'user_id' => $ownerUser->id
            ]);

            // Re-enable foreign key checks
            Schema::enableForeignKeyConstraints();
        } catch (Exception $e) {
            // Re-enable foreign key checks even if error occurs
            Schema::enableForeignKeyConstraints();
            throw $e;
        }
    }
}