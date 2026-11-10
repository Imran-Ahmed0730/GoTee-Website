<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DemoRequest as DemoRequestFormRequest;
use App\Services\Admin\DemoRequestService;
use App\Services\FormSubmissionMailer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class DemoRequestController extends Controller implements HasMiddleware
{
    public function __construct(
        private DemoRequestService $demoRequestService,
        private FormSubmissionMailer $formSubmissionMailer,
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Demo Request View', only: ['index']),
        ];
    }

    public function index(): View
    {
        return view('admin.demo-request.index', [
            'items' => $this->demoRequestService->getAllDemoRequests(),
        ]);
    }

    public function store(DemoRequestFormRequest $request): RedirectResponse
    {
        $demoRequest = $this->demoRequestService->createDemoRequest($request->validated());

        $this->formSubmissionMailer->sendToSubmitterAndReceiver('demo request', $demoRequest->email, [
            'Company' => $demoRequest->company,
            'Contact person' => trim($demoRequest->contacting_person_first_name.' '.$demoRequest->contacting_person_last_name),
            'Email' => $demoRequest->email,
            'Phone' => $demoRequest->phone,
            'Address' => $demoRequest->address,
            'Machine quantity' => $demoRequest->machine_qty,
        ]);

        return back()->with('success', 'Your demo request has been sent successfully.');
    }
}
