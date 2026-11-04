<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_creation_form_is_displayed(): void
    {
        $user = $this->userWithPermission('Role Add');

        $this->actingAs($user)
            ->get(route('admin.role.create'))
            ->assertOk()
            ->assertSee('aria-label="Breadcrumb"', false)
            ->assertSee('fa-circle-plus', false)
            ->assertSee('Create role');
    }

    public function test_role_list_displays_existing_roles(): void
    {
        $user = $this->userWithPermission('Role View');
        $user->givePermissionTo([
            Permission::create(['name' => 'Role Permission Add/Update', 'guard_name' => 'web']),
            Permission::create(['name' => 'Role Update', 'guard_name' => 'web']),
            Permission::create(['name' => 'Role Delete', 'guard_name' => 'web']),
        ]);
        Role::create(['name' => 'Editor', 'guard_name' => 'web']);

        $response = $this->actingAs($user)->get(route('admin.role.index'));

        $response
            ->assertOk()
            ->assertSee('Editor')
            ->assertSee('fa-key', false)
            ->assertSee('data-bs-title="Assign permissions"', false)
            ->assertSee('fa-pen-to-square', false)
            ->assertSee('data-bs-title="Edit role"', false)
            ->assertSee('fa-trash', false)
            ->assertSee('data-bs-title="Delete role"', false);
    }

    public function test_role_can_be_created_with_role_add_permission(): void
    {
        $user = $this->userWithPermission('Role Add');

        $response = $this->actingAs($user)->post(route('admin.role.store'), [
            'name' => 'Editor',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.role.index'));

        $this->assertDatabaseHas('roles', [
            'name' => 'Editor',
            'guard_name' => 'web',
        ]);
    }

    public function test_duplicate_role_names_are_rejected(): void
    {
        $user = $this->userWithPermission('Role Add');
        Role::create(['name' => 'Editor', 'guard_name' => 'web']);

        $response = $this->actingAs($user)
            ->from(route('admin.role.create'))
            ->post(route('admin.role.store'), ['name' => 'Editor']);

        $response
            ->assertSessionHasErrors('name')
            ->assertRedirect(route('admin.role.create'));

        $this->assertDatabaseCount('roles', 1);
    }

    public function test_role_can_be_updated_without_changing_its_name(): void
    {
        $user = $this->userWithPermission('Role Update');
        $role = Role::create(['name' => 'Editor', 'guard_name' => 'web']);

        $this->actingAs($user)
            ->get(route('admin.role.edit', $role))
            ->assertOk()
            ->assertSee('value="Editor"', false);

        $response = $this->actingAs($user)->patch(route('admin.role.update', $role), [
            'name' => 'Editor',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.role.index'));

        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => 'Editor',
        ]);
    }

    public function test_role_can_be_deleted(): void
    {
        $user = $this->userWithPermission('Role Delete');
        $role = Role::create(['name' => 'Editor', 'guard_name' => 'web']);

        $response = $this->actingAs($user)->delete(route('admin.role.destroy', $role));

        $response->assertRedirect(route('admin.role.index'));

        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }

    public function test_permission_assignment_page_displays_permissions(): void
    {
        $user = $this->userWithPermission('Role Permission Add/Update');
        $role = Role::create(['name' => 'Editor', 'guard_name' => 'web']);
        Permission::create(['name' => 'Blog View', 'guard_name' => 'web']);

        $response = $this->actingAs($user)->get(route('admin.role.assign.permission', $role->id));

        $response
            ->assertOk()
            ->assertSee('Permissions for Editor')
            ->assertSee('Blog View');
    }

    public function test_permissions_can_be_assigned_to_a_role(): void
    {
        $user = $this->userWithPermission('Role Permission Add/Update');
        $role = Role::create(['name' => 'Editor', 'guard_name' => 'web']);
        $permission = Permission::create(['name' => 'Blog View', 'guard_name' => 'web']);

        $response = $this->actingAs($user)->post(route('admin.role.assign.permission.submit'), [
            'id' => $role->id,
            'permission' => [$permission->id],
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.role.index'));

        $this->assertDatabaseHas('role_has_permissions', [
            'role_id' => $role->id,
            'permission_id' => $permission->id,
        ]);
    }

    public function test_submitting_no_permissions_clears_the_role_permissions(): void
    {
        $user = $this->userWithPermission('Role Permission Add/Update');
        $role = Role::create(['name' => 'Editor', 'guard_name' => 'web']);
        $permission = Permission::create(['name' => 'Blog View', 'guard_name' => 'web']);
        $role->givePermissionTo($permission);

        $response = $this->actingAs($user)->post(route('admin.role.assign.permission.submit'), [
            'id' => $role->id,
        ]);

        $response->assertRedirect(route('admin.role.index'));

        $this->assertDatabaseMissing('role_has_permissions', [
            'role_id' => $role->id,
            'permission_id' => $permission->id,
        ]);
    }

    public function test_role_routes_are_forbidden_without_the_required_permission(): void
    {
        $user = User::factory()->create(['role' => 1]);

        $this->actingAs($user)
            ->get(route('admin.role.index'))
            ->assertForbidden();
    }

    private function userWithPermission(string $permissionName): User
    {
        $user = User::factory()->create(['role' => 1]);
        $user->givePermissionTo(Permission::create([
            'name' => $permissionName,
            'guard_name' => 'web',
        ]));

        return $user;
    }
}
