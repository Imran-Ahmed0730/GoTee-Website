<?php

namespace App\Services\Admin;

use App\Models\Admin\Faq;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FaqService
{
    /**
     * @param  array{category_id?: string, status?: string}  $filters
     */
    public function getFaqs(array $filters): LengthAwarePaginator
    {
        return Faq::query()
            ->with('category')
            ->when(
                isset($filters['category_id']) && $filters['category_id'] !== '',
                fn ($query) => $query->where('category_id', $filters['category_id'])
            )
            ->when(
                isset($filters['status']) && $filters['status'] !== '',
                fn ($query) => $query->where('status', $filters['status'])
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();
    }

    /**
     * @param  array{category_id: int|string, question: string, answer: string, status: bool|int|string}  $data
     */
    public function createFaq(array $data): Faq
    {
        return Faq::create($data);
    }

    /**
     * @param  array{category_id: int|string, question: string, answer: string, status: bool|int|string}  $data
     */
    public function updateFaq(Faq $faq, array $data): Faq
    {
        $faq->update($data);

        return $faq;
    }

    public function deleteFaq(Faq $faq): void
    {
        $faq->delete();
    }

    public function changeStatus(Faq $faq): bool
    {
        return $faq->update(['status' => ! $faq->status]);
    }
}
