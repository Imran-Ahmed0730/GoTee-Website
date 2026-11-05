<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EmployeeRequest;
use App\Models\Admin\Employee;
use App\Services\Admin\EmployeeService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EmployeeController extends Controller implements HasMiddleware
{
    public function __construct(private readonly EmployeeService $employeeService) {}

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Employee View', only: ['index']),
            new Middleware('permission:Employee Add', only: ['create', 'store']),
            new Middleware('permission:Employee Update', only: ['edit', 'update', 'updateShowAsTeam']),
            new Middleware('permission:Employee Delete', only: ['destroy']),
            new Middleware('permission:Employee Status Change', only: ['changeStatus']),
        ];
    }

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:0,1'],
        ]);

        return view('admin.employee.index', [
            'items' => $this->employeeService->getAllEmployees(
                $filters['search'] ?? null,
                $filters['status'] ?? null,
            ),
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        return view('admin.employee.form', $this->employeeService->getFormOptions());
    }

    public function store(EmployeeRequest $request): RedirectResponse
    {
        $this->employeeService->createEmployee($request->validated());

        return redirect()->route('admin.employee.index')->with('success', 'Employee created successfully.');
    }

    public function edit(Employee $employee): View
    {
        return view('admin.employee.form', [
            ...$this->employeeService->getFormOptions(),
            'item' => $employee->load('user'),
        ]);
    }

    public function update(EmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $this->employeeService->updateEmployee($employee, $request->validated());

        return redirect()->route('admin.employee.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $this->employeeService->deleteEmployee($employee);

        return redirect()->route('admin.employee.index')->with('success', 'Employee deleted successfully.');
    }

    public function changeStatus(Employee $employee): RedirectResponse
    {
        $this->employeeService->changeStatus($employee);

        return back()->with('success', 'Employee status updated successfully.');
    }

    public function updateShowAsTeam(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'show_as_team' => ['required', 'boolean'],
        ]);

        $this->employeeService->updateShowAsTeam($employee, (bool) $validated['show_as_team']);

        return back()->with('success', 'Employee team visibility updated successfully.');
    }
}
