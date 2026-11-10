<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SolutionCategoryRequest;
use App\Models\Admin\SolutionCategory;
use App\Services\Admin\SolutionCategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class SolutionCategoryController extends Controller implements HasMiddleware
{
    public function __construct(private SolutionCategoryService $solutionCategoryService) {}

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Solution Category View', only: ['index']),
            new Middleware('permission:Solution Category Add', only: ['create', 'store']),
            new Middleware('permission:Solution Category Update', only: ['edit', 'update']),
            new Middleware('permission:Solution Category Delete', only: ['destroy']),
            new Middleware('permission:Solution Category Status Change', only: ['changeStatus']),
        ];
    }

    public function index(): View
    {
        return view('admin.solution-category.index', [
            'items' => $this->solutionCategoryService->getAllCategories(),
        ]);
    }

    public function create(): View
    {
        return view('admin.solution-category.form');
    }

    public function store(SolutionCategoryRequest $request): RedirectResponse
    {
        $this->solutionCategoryService->createCategory($request->validated());

        return redirect()->route('admin.solution-category.index')->with('success', 'Solution category created successfully.');
    }

    public function edit(SolutionCategory $solutionCategory): View
    {
        return view('admin.solution-category.form', ['item' => $solutionCategory]);
    }

    public function update(SolutionCategoryRequest $request, SolutionCategory $solutionCategory): RedirectResponse
    {
        $this->solutionCategoryService->updateCategory($solutionCategory, $request->validated());

        return redirect()->route('admin.solution-category.index')->with('success', 'Solution category updated successfully.');
    }

    public function destroy(SolutionCategory $solutionCategory): RedirectResponse
    {
        $this->solutionCategoryService->deleteCategory($solutionCategory);

        return redirect()->route('admin.solution-category.index')->with('success', 'Solution category deleted successfully.');
    }

    public function changeStatus(SolutionCategory $solutionCategory): RedirectResponse
    {
        $this->solutionCategoryService->changeStatus($solutionCategory);

        return back()->with('success', 'Solution category status updated successfully.');
    }
}
