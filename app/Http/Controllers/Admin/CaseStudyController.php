<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CaseStudyRequest;
use App\Models\Admin\CaseStudy;
use App\Services\Admin\CaseStudyService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CaseStudyController extends Controller implements HasMiddleware
{
    public function __construct(private readonly CaseStudyService $caseStudyService) {}

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Case Study View', only: ['index']),
            new Middleware('permission:Case Study Add', only: ['create', 'store']),
            new Middleware('permission:Case Study Update', only: ['edit', 'update']),
            new Middleware('permission:Case Study Delete', only: ['destroy']),
            new Middleware('permission:Case Study Status Change', only: ['changeStatus']),
        ];
    }

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:0,1'],
        ]);

        return view('admin.case-study.index', [
            'items' => $this->caseStudyService->getCaseStudies($filters),
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        return view('admin.case-study.form', $this->caseStudyService->getFormOptions());
    }

    public function store(CaseStudyRequest $request): RedirectResponse
    {
        $this->caseStudyService->createCaseStudy($request->validated());

        return redirect()->route('admin.case-study.index')->with('success', 'Case study created successfully.');
    }

    public function edit(CaseStudy $caseStudy): View
    {
        return view('admin.case-study.form', [
            ...$this->caseStudyService->getFormOptions(),
            'item' => $caseStudy->load(['client', 'testimonial']),
        ]);
    }

    public function update(CaseStudyRequest $request, CaseStudy $caseStudy): RedirectResponse
    {
        $this->caseStudyService->updateCaseStudy($caseStudy, $request->validated());

        return redirect()->route('admin.case-study.index')->with('success', 'Case study updated successfully.');
    }

    public function destroy(CaseStudy $caseStudy): RedirectResponse
    {
        $this->caseStudyService->deleteCaseStudy($caseStudy);

        return redirect()->route('admin.case-study.index')->with('success', 'Case study deleted successfully.');
    }

    public function changeStatus(CaseStudy $caseStudy): RedirectResponse
    {
        $this->caseStudyService->changeStatus($caseStudy);

        return back()->with('success', 'Case study status updated successfully.');
    }
}
