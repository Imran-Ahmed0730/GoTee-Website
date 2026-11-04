<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FaqRequest;
use App\Models\Admin\Faq;
use App\Models\Admin\FaqCategory;
use App\Services\Admin\FaqService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class FaqController extends Controller implements HasMiddleware
{
    public function __construct(private FaqService $faqService) {}

    public static function middleware(): array
    {
        return [
            new Middleware('permission:FAQ Add', only: ['create', 'store']),
            new Middleware('permission:FAQ View', only: ['index']),
            new Middleware('permission:FAQ Update', only: ['edit', 'update']),
            new Middleware('permission:FAQ Delete', only: ['destroy']),
            new Middleware('permission:FAQ Status Change', only: ['changeStatus']),
        ];
    }

    public function index(Request $request): View
    {
        return view('admin.faq.index', [
            'items' => $this->faqService->getFaqs($request->only(['category_id', 'status'])),
            'categories' => FaqCategory::query()->orderBy('name')->get(),
            'filters' => $request->only(['category_id', 'status']),
        ]);
    }

    public function create(): View
    {
        return view('admin.faq.form', [
            'categories' => FaqCategory::query()->orderBy('name')->get(),
        ]);
    }

    public function store(FaqRequest $request): RedirectResponse
    {
        $this->faqService->createFaq($request->validated());

        return redirect()->route('admin.faq.index')->with('success', 'FAQ created successfully.');
    }

    public function edit(Faq $faq): View
    {
        return view('admin.faq.form', [
            'item' => $faq,
            'categories' => FaqCategory::query()->orderBy('name')->get(),
        ]);
    }

    public function update(FaqRequest $request, Faq $faq): RedirectResponse
    {
        $this->faqService->updateFaq($faq, $request->validated());

        return redirect()->route('admin.faq.index')->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $this->faqService->deleteFaq($faq);

        return redirect()->route('admin.faq.index')->with('success', 'FAQ deleted successfully.');
    }

    public function changeStatus(Faq $faq): RedirectResponse
    {
        $this->faqService->changeStatus($faq);

        return back()->with('success', 'FAQ status updated successfully.');
    }
}
