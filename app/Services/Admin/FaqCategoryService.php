<?php

namespace App\Services\Admin;

use App\Models\Admin\FaqCategory;
use Illuminate\Database\Eloquent\Collection;

class FaqCategoryService
{
    /**
     * @return Collection<int, FaqCategory>
     */
    public function getAllCategories(): Collection
    {
        return FaqCategory::query()->withCount('faqs')->latest()->get();
    }

    /**
     * @param  array{name: string, status: bool|int|string}  $data
     */
    public function createCategory(array $data): FaqCategory
    {
        return FaqCategory::create($data);
    }

    /**
     * @param  array{name: string, status: bool|int|string}  $data
     */
    public function updateCategory(FaqCategory $category, array $data): FaqCategory
    {
        $category->update($data);

        return $category;
    }

    public function deleteCategory(FaqCategory $category): void
    {
        $category->delete();
    }

    public function changeStatus(FaqCategory $category): bool
    {
        return $category->update(['status' => ! $category->status]);
    }
}
