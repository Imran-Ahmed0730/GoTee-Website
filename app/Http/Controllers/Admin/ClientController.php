<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClientRequest;
use App\Models\Admin\Client;
use App\Services\Admin\ClientService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ClientController extends Controller implements HasMiddleware
{
    private $clientService;

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Client Add', only: ['create', 'store']),
            new Middleware('permission:Client View', only: ['index']),
            new Middleware('permission:Client Update', only: ['edit', 'update']),
            new Middleware('permission:Client Delete', only: ['destroy']),
            new Middleware('permission:Client Status Change', only: ['changeStatus']),
        ];
    }

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['items'] = $this->clientService->getAllClients();

        return view('admin.client.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.client.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientRequest $request)
    {
        $this->clientService->createClient($request->validated());

        return redirect()->route('admin.client.index')->with('success', 'Client created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return view('admin.client.form', [
            'item' => $client,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientRequest $request, Client $client)
    {
        $this->clientService->updateClient($client, $request->validated());

        return redirect()->route('admin.client.index')->with('success', 'Client updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $this->clientService->deleteClient($client);

        return redirect()->route('admin.client.index')->with('success', 'Client deleted successfully.');
    }

    public function changeStatus(Client $client): RedirectResponse
    {
        $this->clientService->changeStatus($client);

        return back()->with('success', 'Client status updated successfully.');
    }
}
