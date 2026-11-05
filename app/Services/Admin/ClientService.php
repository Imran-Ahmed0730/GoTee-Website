<?php

namespace App\Services\Admin;

use App\Models\Admin\Client;

class ClientService
{
    /**
     * Create a new class instance.
     */
    public function getAllClients()
    {
        return Client::query()->orderBy('name')->get();
    }

    public function createClient(array $data)
    {
        return Client::create($data);
    }

    public function updateClient(Client $client, array $data)
    {
        $client->update($data);

        return $client;
    }

    public function deleteClient(Client $client)
    {
        $client->delete();
    }

    public function changeStatus(Client $client)
    {
        $client->update(['status' => ! (bool) $client->getRawOriginal('status')]);

        return $client;
    }
}
