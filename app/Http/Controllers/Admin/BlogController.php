<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogRequest;
use App\Models\Admin\Blog;
use App\Models\Admin\BlogCategory;
use App\Services\Admin\BlogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class BlogController extends Controller implements HasMiddleware
{
    public function __construct(private BlogService $blogService) {}

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Blog Add', only: ['create', 'store']),
            new Middleware('permission:Blog View', only: ['index']),
            new Middleware('permission:Blog Update', only: ['edit', 'update']),
            new Middleware('permission:Blog Delete', only: ['destroy']),
            new Middleware('permission:Blog Status Change', only: ['changeStatus']),
        ];
    }

    public function index(Request $request): View
    {
        return view('admin.blog.index', [
            'items' => $this->blogService->getBlogs($request->only(['category_id', 'status'])),
            'categories' => BlogCategory::query()->orderBy('name')->get(),
            'filters' => $request->only(['category_id', 'status']),
        ]);
    }

    public function create(): View
    {
        return view('admin.blog.form', [
            'categories' => BlogCategory::query()->orderBy('name')->get(),
        ]);
    }

    public function store(BlogRequest $request): RedirectResponse
    {
        $this->blogService->createBlog($request->validated());

        return redirect()->route('admin.blog.index')->with('success', 'Blog created successfully.');
    }

    public function edit(Blog $blog): View
    {
        return view('admin.blog.form', [
            'item' => $blog,
            'categories' => BlogCategory::query()->orderBy('name')->get(),
        ]);
    }

    public function update(BlogRequest $request, Blog $blog): RedirectResponse
    {
        $this->blogService->updateBlog($blog, $request->validated());

        return redirect()->route('admin.blog.index')->with('success', 'Blog updated successfully.');
    }

    public function destroy(Blog $blog): RedirectResponse
    {
        $this->blogService->deleteBlog($blog);

        return redirect()->route('admin.blog.index')->with('success', 'Blog deleted successfully.');
    }

    public function changeStatus(Blog $blog): RedirectResponse
    {
        $this->blogService->changeStatus($blog);

        return back()->with('success', 'Blog status updated successfully.');
    }
}
