<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BannerRequest;
use App\Models\Admin\Banner;
use App\Services\Admin\BannerService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;

class BannerController extends Controller implements HasMiddleware
{
    private $bannerService;

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Banner Add', only: ['create', 'store']),
            new Middleware('permission:Banner View', only: ['index']),
            new Middleware('permission:Banner Update', only: ['edit', 'update']),
            new Middleware('permission:Banner Delete', only: ['destroy']),
            new Middleware('permission:Banner Status Change', only: ['changeStatus']),
        ];
    }

    public function __construct(BannerService $bannerService)
    {
        $this->bannerService = $bannerService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'position' => ['nullable', Rule::in(['1', '2', '3'])],
            'status' => ['nullable', Rule::in(['0', '1'])],
        ]);

        return view('admin.banner.index', [
            'items' => $this->bannerService->getAllBanners($filters),
            'filters' => $filters,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.banner.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BannerRequest $request)
    {
        $this->bannerService->createBanner($request->validated());

        return redirect()->route('admin.banner.index')->with('success', 'Banner created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner)
    {
        return view('admin.banner.form', ['item' => $banner]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BannerRequest $request, Banner $banner)
    {
        $this->bannerService->updateBanner($banner, $request->validated());

        return redirect()->route('admin.banner.index')->with('success', 'Banner updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        $this->bannerService->deleteBanner($banner);

        return redirect()->route('admin.banner.index')->with('success', 'Banner deleted successfully.');
    }

    public function changeStatus(Banner $banner): RedirectResponse
    {
        $this->bannerService->changeStatus($banner);

        return back()->with('success', 'Banner status updated successfully.');
    }
}
