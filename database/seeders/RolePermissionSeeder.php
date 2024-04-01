<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creating a administrator user
        $user = User::create([
            'name' => 'administrator',
            'email' => 'administrator@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
        // Creating role
        $role = Role::create(['name' => 'administrator']);
        Role::create(['name' => 'user']);
        // Assigning role
        $user->assignRole('administrator', 'user');
        // Permissions list
        $permissions = [
            ['name' => 'user list'],
            ['name' => 'create user'],
            ['name' => 'update user'],
            ['name' => 'delete user'],
            ['name' => 'role list'],
            ['name' => 'create role'],
            ['name' => 'update role'],
            ['name' => 'delete role'],
            ['name' => 'task list'],
            ['name' => 'create task'],
            ['name' => 'update task'],
            ['name' => 'delete task'],
        ];
        // Inserting permission to the Permission table
        foreach($permissions as $permission){
            Permission::create($permission);
        }
        $role->syncPermissions($permissions);
    }
}
