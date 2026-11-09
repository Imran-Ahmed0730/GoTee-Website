<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ContactRequest;
use App\Services\Admin\ContactService;
use App\Services\FormSubmissionMailer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class ContactController extends Controller implements HasMiddleware
{
    public function __construct(
        private ContactService $contactService,
        private FormSubmissionMailer $formSubmissionMailer,
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Contact View', only: ['index']),
        ];
    }

    public function index(): View
    {
        return view('admin.contact.index', [
            'items' => $this->contactService->getAllContacts(),
        ]);
    }

    public function store(ContactRequest $request): RedirectResponse
    {
        $contact = $this->contactService->createContact($request->validated());

        $this->formSubmissionMailer->sendToSubmitterAndReceiver('contact request', $contact->email, [
            'Name' => trim($contact->first_name.' '.$contact->last_name),
            'Email' => $contact->email,
            'Phone' => $contact->phone,
            'Company' => $contact->company,
            'Message' => $contact->note,
        ]);

        return back()->with('success', 'Your message has been sent successfully.');
    }
}
