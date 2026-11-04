<?php

namespace Database\Seeders\Admin;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        $permissions = Permission::query()
            ->where('guard_name', 'web')
            ->get();
        $adminRole->syncPermissions($permissions);

        $admin = User::query()
            ->where('email', 'admin@example.com')
            ->firstOrFail();

        $admin->assignRole($adminRole);
    }
}
