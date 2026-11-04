<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RolePermissionAssignRequest;
use App\Http\Requests\Admin\RoleRequest;
use App\Services\Admin\RoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RoleController extends Controller implements HasMiddleware
{
    private $roleService;
    public function __construct(RoleService $roleService) {

        $this->roleService = $roleService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Role Add', only: ['create', 'store']),
            new Middleware('permission:Role View', only: ['index']),
            new Middleware('permission:Role Update', only: ['edit', 'update']),
            new Middleware('permission:Role Delete', only: ['destroy']),
            new Middleware('permission:Role Permission Add/Update', only: ['assignPermission', 'assignPermissionSubmit']),
        ];
    }

    public function index(): View
    {
        return view('admin.role.index', ['items' => $this->roleService->getRoles()]);
    }

    public function create(): View
    {
        return view('admin.role.form');
    }

    public function store(RoleRequest $request): RedirectResponse
    {
        $this->roleService->createRole($request->validated());

        return redirect()->route('admin.role.index')->with('success', 'Role created successfully');
    }

    public function edit(Role $role): View
    {
        return view('admin.role.form', ['item' => $role]);
    }

    public function update(RoleRequest $request, Role $role): RedirectResponse
    {
        $this->roleService->updateRole($role, $request->validated());

        return redirect()->route('admin.role.index')->with('success', 'Role updated successfully');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $this->roleService->deleteRole($role);

        return redirect()->route('admin.role.index')->with('success', 'Role deleted successfully');
    }

    public function assignPermission(int $id): View
    {
        $data = $this->roleService->assignPermissionsForRole($id);

        return view('admin.role-permission-assign.index', $data);
    }

    public function assignPermissionSubmit(RolePermissionAssignRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $this->roleService->assignPermissions($data['id'], $data['permission'] ?? []);

        return redirect()->route('admin.role.index')->with('success', 'Permission assigned to the role successfully');
    }
}
