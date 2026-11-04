<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogCategoryRequest;
use App\Models\Admin\BlogCategory;
use App\Services\Admin\BlogCategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class BlogCategoryController extends Controller implements HasMiddleware
{
    public function __construct(private BlogCategoryService $blogCategoryService) {}

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Blog Category Add', only: ['create', 'store']),
            new Middleware('permission:Blog Category View', only: ['index']),
            new Middleware('permission:Blog Category Update', only: ['edit', 'update']),
            new Middleware('permission:Blog Category Delete', only: ['destroy']),
            new Middleware('permission:Blog Category Status Change', only: ['changeStatus']),
        ];
    }

    public function index(): View
    {
        return view('admin.blog.category.index', [
            'items' => $this->blogCategoryService->getAllCategories(),
        ]);
    }

    public function create(): View
    {
        return view('admin.blog.category.form');
    }

    public function store(BlogCategoryRequest $request): RedirectResponse
    {
        $this->blogCategoryService->createCategory($request->validated());

        return redirect()->route('admin.blog.category.index')->with('success', 'Blog category created successfully.');
    }

    public function edit(BlogCategory $category): View
    {
        return view('admin.blog.category.form', ['item' => $category]);
    }

    public function update(BlogCategoryRequest $request, BlogCategory $category): RedirectResponse
    {
        $this->blogCategoryService->updateCategory($category, $request->validated());

        return redirect()->route('admin.blog.category.index')->with('success', 'Blog category updated successfully.');
    }

    public function destroy(BlogCategory $category): RedirectResponse
    {
        $this->blogCategoryService->deleteCategory($category);

        return redirect()->route('admin.blog.category.index')->with('success', 'Blog category and its blogs deleted successfully.');
    }

    public function changeStatus(BlogCategory $category): RedirectResponse
    {
        $this->blogCategoryService->changeStatus($category);

        return back()->with('success', 'Blog category status updated successfully.');
    }
}
