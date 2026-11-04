<?php

namespace App\Services\Admin;

use Spatie\Permission\Models\Permission;

class PermissionService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getAllPermissions()
    {
        return Permission::all();
    }

    public function findPermission($id)
    {
        return Permission::findOrFail($id);
    }

    public function createPermission(array $data)
    {
        return Permission::create($data);
    }

    public function updatePermission(Permission $permission, array $data)
    {
        return $permission->update($data);
    }

    public function deletePermission($id)
    {
        return Permission::where('id', $id)->delete();
    }

    public function toggleStatus(Permission $permission)
    {
        $status = $permission->status == 1 ? 0 : 1;

        return $permission->update(['status' => $status]);
    }
}
