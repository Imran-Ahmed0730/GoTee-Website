<?php

namespace App\Services\Admin;

use App\Models\Admin\BlogCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use RuntimeException;
use Throwable;

class BlogCategoryService
{
    /**
     * @return Collection<int, BlogCategory>
     */
    public function getAllCategories(): Collection
    {
        return BlogCategory::query()->withCount('blogs')->latest()->get();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function createCategory(array $data): BlogCategory
    {
        $storedImages = [];

        try {
            if (($data['icon'] ?? null) instanceof UploadedFile) {
                $data['icon'] = updateImagePath($data['icon'], null, 'blog/categories/icons');
                $storedImages[] = $data['icon'];
            }

            if (($data['cover_photo'] ?? null) instanceof UploadedFile) {
                $data['cover_photo'] = updateImagePath($data['cover_photo'], null, 'blog/categories/covers');
                $storedImages[] = $data['cover_photo'];
            }

            return BlogCategory::create($data);
        } catch (Throwable $exception) {
            $this->deleteImages($storedImages);

            throw $exception;
        }
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateCategory(BlogCategory $category, array $data): BlogCategory
    {
        $oldImages = [];
        $storedImages = [];

        try {
            if (($data['icon'] ?? null) instanceof UploadedFile) {
                $oldImages[] = $category->icon;
                $data['icon'] = updateImagePath($data['icon'], null, 'blog/categories/icons');
                $storedImages[] = $data['icon'];
            }

            if (($data['cover_photo'] ?? null) instanceof UploadedFile) {
                $oldImages[] = $category->cover_photo;
                $data['cover_photo'] = updateImagePath($data['cover_photo'], null, 'blog/categories/covers');
                $storedImages[] = $data['cover_photo'];
            }

            $category->update($data);
        } catch (Throwable $exception) {
            $this->deleteImages($storedImages);

            throw $exception;
        }

        $this->deleteImages($oldImages);

        return $category;
    }

    public function deleteCategory(BlogCategory $category): void
    {
        $blogImages = $category->blogs()
            ->get(['thumbnail', 'cover_photo'])
            ->flatMap(fn ($blog): array => [$blog->thumbnail, $blog->cover_photo])
            ->push($category->cover_photo)
            ->push($category->icon)
            ->all();

        $category->delete();

        $this->deleteImages($blogImages);
    }

    public function changeStatus(BlogCategory $category): bool
    {
        return $category->update(['status' => ! $category->status]);
    }

    private function deleteImage(?string $path): void
    {
        if ($path !== null && is_file(public_path($path)) && ! unlink(public_path($path))) {
            throw new RuntimeException('Unable to remove a blog category image.');
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
