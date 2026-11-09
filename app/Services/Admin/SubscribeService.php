<?php

namespace App\Services\Admin;

use App\Models\Admin\Subscribe;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SubscribeService
{
    public function getAllSubscriptions(): LengthAwarePaginator
    {
        return Subscribe::query()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(15);
    }

    /**
     * @param  array{email: string}  $data
     */
    public function createSubscription(array $data): Subscribe
    {
        return Subscribe::create($data);
    }
}
