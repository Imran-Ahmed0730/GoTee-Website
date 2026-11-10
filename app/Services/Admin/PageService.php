<?php

namespace App\Services\Admin;

use App\Models\Admin\Page;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PageService
{
    public function getAllPages(): LengthAwarePaginator
    {
        return Page::query()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(15);
    }

    /**
     * @param  array{title: string, description?: string|null, status: bool|int|string}  $data
     */
    public function createPage(array $data): Page
    {
        return Page::create($data);
    }

    /**
     * @param  array{title: string, description?: string|null, status: bool|int|string}  $data
     */
    public function updatePage(Page $page, array $data): Page
    {
        $page->update($data);

        return $page->refresh();
    }

    public function deletePage(Page $page): void
    {
        $page->delete();
    }

    public function changeStatus(Page $page): bool
    {
        return $page->update(['status' => ! (bool) $page->getRawOriginal('status')]);
    }
}
