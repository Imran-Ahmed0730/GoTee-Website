<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TestimonialRequest;
use App\Models\Admin\Testimonial;
use App\Services\Admin\TestimonialService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TestimonialController extends Controller implements HasMiddleware
{
    public function __construct(private readonly TestimonialService $testimonialService) {}

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Testimonial View', only: ['index']),
            new Middleware('permission:Testimonial Add', only: ['create', 'store']),
            new Middleware('permission:Testimonial Update', only: ['edit', 'update']),
            new Middleware('permission:Testimonial Delete', only: ['destroy']),
            new Middleware('permission:Testimonial Status Change', only: ['changeStatus']),
        ];
    }

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:0,1'],
        ]);

        return view('admin.testimonial.index', [
            'items' => $this->testimonialService->getTestimonials(
                $filters['search'] ?? null,
                $filters['status'] ?? null,
            ),
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        return view('admin.testimonial.form');
    }

    public function store(TestimonialRequest $request): RedirectResponse
    {
        $this->testimonialService->createTestimonial($request->validated());

        return redirect()->route('admin.testimonial.index')->with('success', 'Testimonial created successfully.');
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonial.form', ['item' => $testimonial]);
    }

    public function update(TestimonialRequest $request, Testimonial $testimonial): RedirectResponse
    {
        $this->testimonialService->updateTestimonial($testimonial, $request->validated());

        return redirect()->route('admin.testimonial.index')->with('success', 'Testimonial updated successfully.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $this->testimonialService->deleteTestimonial($testimonial);

        return redirect()->route('admin.testimonial.index')->with('success', 'Testimonial deleted successfully.');
    }

    public function changeStatus(Testimonial $testimonial): RedirectResponse
    {
        $this->testimonialService->changeStatus($testimonial);

        return back()->with('success', 'Testimonial status updated successfully.');
    }
}
