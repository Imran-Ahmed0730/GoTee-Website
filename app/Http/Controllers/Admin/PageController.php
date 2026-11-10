<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageRequest;
use App\Models\Admin\Page;
use App\Services\Admin\PageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class PageController extends Controller implements HasMiddleware
{
    public function __construct(private PageService $pageService) {}

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Page View', only: ['index']),
            new Middleware('permission:Page Add', only: ['create', 'store']),
            new Middleware('permission:Page Update', only: ['edit', 'update']),
            new Middleware('permission:Page Delete', only: ['destroy']),
            new Middleware('permission:Page Status Change', only: ['changeStatus']),
        ];
    }

    public function index(): View
    {
        return view('admin.page.index', [
            'items' => $this->pageService->getAllPages(),
        ]);
    }

    public function create(): View
    {
        return view('admin.page.form');
    }

    public function store(PageRequest $request): RedirectResponse
    {
        $this->pageService->createPage($request->validated());

        return redirect()->route('admin.page.index')->with('success', 'Page created successfully.');
    }

    public function edit(Page $page): View
    {
        return view('admin.page.form', ['item' => $page]);
    }

    public function update(PageRequest $request, Page $page): RedirectResponse
    {
        $this->pageService->updatePage($page, $request->validated());

        return redirect()->route('admin.page.index')->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $this->pageService->deletePage($page);

        return redirect()->route('admin.page.index')->with('success', 'Page deleted successfully.');
    }

    public function changeStatus(Page $page): RedirectResponse
    {
        $this->pageService->changeStatus($page);

        return back()->with('success', 'Page status updated successfully.');
    }
}
