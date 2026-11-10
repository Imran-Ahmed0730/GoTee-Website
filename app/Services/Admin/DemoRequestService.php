<?php

namespace App\Services\Admin;

use App\Models\Admin\DemoRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DemoRequestService
{
    public function getAllDemoRequests(): LengthAwarePaginator
    {
        return DemoRequest::query()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(15);
    }

    /**
     * @param  array{company: string, contacting_person_first_name: string, contacting_person_last_name?: string|null, email: string, phone: string, address: string, machine_qty: int}  $data
     */
    public function createDemoRequest(array $data): DemoRequest
    {
        return DemoRequest::create($data);
    }
}
