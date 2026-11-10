<?php

namespace App\Services\Admin;

use App\Models\Admin\Contact;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ContactService
{
    public function getAllContacts(): LengthAwarePaginator
    {
        return Contact::query()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(15);
    }

    /**
     * @param  array{first_name: string, last_name?: string|null, email: string, phone: string, company: string, note: string}  $data
     */
    public function createContact(array $data): Contact
    {
        return Contact::create($data);
    }
}
