<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FaqCategoryRequest;
use App\Models\Admin\FaqCategory;
use App\Services\Admin\FaqCategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class FaqCategoryController extends Controller implements HasMiddleware
{
    public function __construct(private FaqCategoryService $faqCategoryService) {}

    public static function middleware(): array
    {
        return [
            new Middleware('permission:FAQ Category Add', only: ['create', 'store']),
            new Middleware('permission:FAQ Category View', only: ['index']),
            new Middleware('permission:FAQ Category Update', only: ['edit', 'update']),
            new Middleware('permission:FAQ Category Delete', only: ['destroy']),
            new Middleware('permission:FAQ Category Status Change', only: ['changeStatus']),
        ];
    }

    public function index(): View
    {
        return view('admin.faq.category.index', [
            'items' => $this->faqCategoryService->getAllCategories(),
        ]);
    }

    public function create(): View
    {
        return view('admin.faq.category.form');
    }

    public function store(FaqCategoryRequest $request): RedirectResponse
    {
        $this->faqCategoryService->createCategory($request->validated());

        return redirect()->route('admin.faq.category.index')->with('success', 'FAQ category created successfully.');
    }

    public function edit(FaqCategory $category): View
    {
        return view('admin.faq.category.form', ['item' => $category]);
    }

    public function update(FaqCategoryRequest $request, FaqCategory $category): RedirectResponse
    {
        $this->faqCategoryService->updateCategory($category, $request->validated());

        return redirect()->route('admin.faq.category.index')->with('success', 'FAQ category updated successfully.');
    }

    public function destroy(FaqCategory $category): RedirectResponse
    {
        $this->faqCategoryService->deleteCategory($category);

        return redirect()->route('admin.faq.category.index')->with('success', 'FAQ category and its entries deleted successfully.');
    }

    public function changeStatus(FaqCategory $category): RedirectResponse
    {
        $this->faqCategoryService->changeStatus($category);

        return back()->with('success', 'FAQ category status updated successfully.');
    }
}
