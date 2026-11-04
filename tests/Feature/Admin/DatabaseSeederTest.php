<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_creates_admin_with_all_permissions(): void
    {
        $this->seed();

        $admin = User::query()->where('email', 'admin@example.com')->firstOrFail();
        $adminRole = Role::findByName('Admin');

        $this->assertSame(1, $admin->role);
        $this->assertTrue($admin->hasRole($adminRole));
        $this->assertTrue($admin->can('Role Add'));
        $this->assertCount(Permission::query()->where('guard_name', 'web')->count(), $adminRole->permissions);
    }

    public function test_database_seeder_can_run_more_than_once(): void
    {
        $this->seed();
        $permissionCount = Permission::query()->count();
        $this->seed();

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('roles', 1);
        $this->assertDatabaseCount('permissions', $permissionCount);
    }
}
