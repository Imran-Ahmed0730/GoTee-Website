<?php

namespace App\Services\Admin;

use App\Models\Admin\SolutionCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use RuntimeException;
use Throwable;

class SolutionCategoryService
{
    public function getAllCategories(): LengthAwarePaginator
    {
        return SolutionCategory::query()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(15);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function createCategory(array $data): SolutionCategory
    {
        $storedImages = [];

        try {
            foreach (['image' => 'solution-categories/images', 'icon' => 'solution-categories/icons'] as $field => $directory) {
                if (($data[$field] ?? null) instanceof UploadedFile) {
                    $data[$field] = updateImagePath($data[$field], null, $directory);
                    $storedImages[] = $data[$field];
                }
            }

            return SolutionCategory::create($data);
        } catch (Throwable $exception) {
            $this->deleteImages($storedImages);

            throw $exception;
        }
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateCategory(SolutionCategory $category, array $data): SolutionCategory
    {
        $oldImages = [];
        $storedImages = [];

        try {
            foreach (['image' => 'solution-categories/images', 'icon' => 'solution-categories/icons'] as $field => $directory) {
                if (($data[$field] ?? null) instanceof UploadedFile) {
                    $oldImages[] = $category->{$field};
                    $data[$field] = updateImagePath($data[$field], null, $directory);
                    $storedImages[] = $data[$field];
                }
            }

            $category->update($data);
        } catch (Throwable $exception) {
            $this->deleteImages($storedImages);

            throw $exception;
        }

        $this->deleteImages($oldImages);

        return $category->refresh();
    }

    public function deleteCategory(SolutionCategory $category): void
    {
        $images = [$category->image, $category->icon];
        $category->delete();
        $this->deleteImages($images);
    }

    public function changeStatus(SolutionCategory $category): bool
    {
        return $category->update(['status' => ! (bool) $category->getRawOriginal('status')]);
    }

    private function deleteImage(?string $path): void
    {
        if ($path !== null && is_file(public_path($path)) && ! unlink(public_path($path))) {
            throw new RuntimeException('Unable to remove a solution category image.');
        }
    }

    /**
     * @param  array<int, string|null>  $paths
     */
    private function deleteImages(array $paths): void
    {
        foreach (array_filter($paths) as $path) {
            $this->deleteImage($path);
        }
    }
}
