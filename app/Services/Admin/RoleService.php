<?php

namespace App\Services\Admin;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleService
{
    public function getRoles(): Collection
    {
        return Role::query()
            ->withCount('permissions')
            ->orderBy('name')
            ->get();
    }

    /**
     * @param  array{name: string}  $data
     */
    public function createRole(array $data): Role
    {
        return Role::create([
            'name' => $data['name'],
            'guard_name' => 'web',
        ]);
    }

    /**
     * @param  array{name: string}  $data
     */
    public function updateRole(Role $role, array $data): bool
    {
        return $role->update([
            'name' => $data['name'],
        ]);
    }

    public function findRole(int $id): Role
    {
        return Role::findOrFail($id);
    }

    public function deleteRole(Role $role): bool
    {
        return $role->delete();
    }

    /**
     * @return array{
     *     role: Role,
     *     permissionsGrouped: \Illuminate\Support\Collection<string, Collection<int, Permission>>,
     *     selectedPermissionIds: array<int, int>
     * }
     */
    public function assignPermissionsForRole(int $id): array
    {
        $role = $this->findRole($id);
        $permissions = Permission::query()->orderBy('name')->get();

        return [
            'role' => $role,
            'permissionsGrouped' => $permissions->groupBy(
                fn (Permission $permission): string => Str::before($permission->name, ' ')
            ),
            'selectedPermissionIds' => $role->permissions->modelKeys(),
        ];
    }

    /**
     * @param  array<int, int>  $permissionIds
     */
    public function assignPermissions(int $roleId, array $permissionIds): void
    {
        $role = $this->findRole($roleId);
        $permissions = Permission::query()
            ->where('guard_name', $role->guard_name)
            ->whereKey($permissionIds)
            ->get();

        $role->syncPermissions($permissions);
    }
}
