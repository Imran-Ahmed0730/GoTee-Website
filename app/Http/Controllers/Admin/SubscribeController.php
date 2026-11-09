<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SubscribeRequest;
use App\Services\Admin\SubscribeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class SubscribeController extends Controller implements HasMiddleware
{
    public function __construct(private SubscribeService $subscribeService) {}

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Subscribe View', only: ['index']),
        ];
    }

    public function index(): View
    {
        return view('admin.subscribe.index', [
            'items' => $this->subscribeService->getAllSubscriptions(),
        ]);
    }

    public function store(SubscribeRequest $request): RedirectResponse
    {
        $this->subscribeService->createSubscription($request->validated());

        return back()->with('success', 'Subscribed to our newsletter successfully.');
    }
}
