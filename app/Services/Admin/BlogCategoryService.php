<?php

namespace App\Services\Admin;

use App\Models\Admin\BlogCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
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
        $storedFiles = [];
        $storedIcons = [];

        try {
            if (($data['icon'] ?? null) instanceof UploadedFile) {
                $data['icon'] = updateImagePath($data['icon'], null, 'blog-categories/icons');
                $storedIcons[] = $data['icon'];
            }

            if (($data['cover_photo'] ?? null) instanceof UploadedFile) {
                $data['cover_photo'] = $this->storeImage($data['cover_photo']);
                $storedFiles[] = $data['cover_photo'];
            }

            return BlogCategory::create($data);
        } catch (Throwable $exception) {
            $this->deleteStoredFiles($storedFiles);
            $this->deleteImages($storedIcons);

            throw $exception;
        }
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateCategory(BlogCategory $category, array $data): BlogCategory
    {
        $oldIcon = $category->icon;
        $oldCoverPhoto = $category->cover_photo;
        $storedFiles = [];
        $storedIcons = [];

        try {
            if (($data['icon'] ?? null) instanceof UploadedFile) {
                $data['icon'] = updateImagePath($data['icon'], null, 'blog-categories/icons');
                $storedIcons[] = $data['icon'];
            }

            if (($data['cover_photo'] ?? null) instanceof UploadedFile) {
                $data['cover_photo'] = $this->storeImage($data['cover_photo']);
                $storedFiles[] = $data['cover_photo'];
            }

            $category->update($data);
        } catch (Throwable $exception) {
            $this->deleteStoredFiles($storedFiles);
            $this->deleteImages($storedIcons);

            throw $exception;
        }

        if ($oldCoverPhoto !== $category->cover_photo) {
            $this->deleteStoredFiles([$oldCoverPhoto]);
        }

        if ($oldIcon !== $category->icon) {
            $this->deleteImage($oldIcon);
        }

        return $category;
    }

    public function deleteCategory(BlogCategory $category): void
    {
        $blogImages = $category->blogs()
            ->get(['thumbnail', 'cover_photo'])
            ->flatMap(fn ($blog): array => [$blog->thumbnail, $blog->cover_photo])
            ->push($category->cover_photo)
            ->all();

        $category->delete();

        $this->deleteStoredFiles($blogImages);
        $this->deleteImage($category->icon);
    }

    public function changeStatus(BlogCategory $category): bool
    {
        return $category->update(['status' => ! $category->status]);
    }

    private function storeImage(UploadedFile $image): string
    {
        $path = $image->store('blog-categories', 'public');

        if (! is_string($path)) {
            throw new RuntimeException('Unable to store the blog category cover photo.');
        }

        return $path;
    }

    /**
     * @param  array<int, string|null>  $paths
     */
    private function deleteStoredFiles(array $paths): void
    {
        $disk = Storage::disk('public');

        foreach (array_filter($paths) as $path) {
            if ($disk->exists($path) && ! $disk->delete($path)) {
                throw new RuntimeException('Unable to remove a stored blog image.');
            }
        }
    }

    private function deleteImage(?string $path): void
    {
        if ($path !== null && File::exists(public_path($path)) && ! File::delete(public_path($path))) {
            throw new RuntimeException('Unable to remove the blog category icon.');
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
