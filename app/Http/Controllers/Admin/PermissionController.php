<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionRequest;
use App\Services\Admin\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PermissionController extends Controller implements HasMiddleware
{
    private $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Permission Add', only: ['create', 'store']),
            new Middleware('permission:Permission View', only: ['index']),
            new Middleware('permission:Permission Update', only: ['edit', 'update']),
            new Middleware('permission:Permission Delete', only: ['destroy']),
            new Middleware('permission:Permission Status Change', only: ['changeStatus']),
        ];
    }

    public function index()
    {
        $data['items'] = $this->permissionService->getAllPermissions();

        return view('admin.permission.index', $data);
    }

    public function create()
    {
        return view('admin.permission.form');
    }

    public function store(PermissionRequest $request)
    {
        $this->permissionService->createPermission($request->validated());

        return redirect()->route('admin.permission.index')->with('success', 'Permission created successfully');
    }

    public function edit(string $id)
    {
        $data['item'] = $this->permissionService->findPermission($id);

        return view('admin.permission.form', $data);
    }

    public function update(PermissionRequest $request)
    {
        $permission = $this->permissionService->findPermission($request->id);
        $this->permissionService->updatePermission($permission, $request->validated());

        return redirect()->route('admin.permission.index')->with('success', 'Permission updated successfully');
    }

    public function destroy(Request $request)
    {
        $this->permissionService->deletePermission($request->id);

        return redirect()->route('admin.permission.index')->with('success', 'Permission deleted successfully');
    }

    public function changeStatus($id)
    {
        $permission = $this->permissionService->findPermission($id);
        $this->permissionService->toggleStatus($permission);

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
