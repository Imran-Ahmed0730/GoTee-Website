<?php

namespace App\Services\Admin;

use App\Models\Admin\Blog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class BlogService
{
    /**
     * @param  array{category_id?: string, status?: string}  $filters
     */
    public function getBlogs(array $filters): LengthAwarePaginator
    {
        return Blog::query()
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
     * @param  array<string, mixed>  $data
     */
    public function createBlog(array $data): Blog
    {
        $storedFiles = [];

        try {
            $data['thumbnail'] = $this->storeImage($data['thumbnail'], 'blogs/thumbnails');
            $storedFiles[] = $data['thumbnail'];

            if (($data['cover_photo'] ?? null) instanceof UploadedFile) {
                $data['cover_photo'] = $this->storeImage($data['cover_photo'], 'blogs/covers');
                $storedFiles[] = $data['cover_photo'];
            }

            return Blog::create($data);
        } catch (Throwable $exception) {
            $this->deleteStoredFiles($storedFiles);

            throw $exception;
        }
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateBlog(Blog $blog, array $data): Blog
    {
        $oldFiles = [];
        $storedFiles = [];

        try {
            if (($data['thumbnail'] ?? null) instanceof UploadedFile) {
                $oldFiles[] = $blog->thumbnail;
                $data['thumbnail'] = $this->storeImage($data['thumbnail'], 'blogs/thumbnails');
                $storedFiles[] = $data['thumbnail'];
            }

            if (($data['cover_photo'] ?? null) instanceof UploadedFile) {
                $oldFiles[] = $blog->cover_photo;
                $data['cover_photo'] = $this->storeImage($data['cover_photo'], 'blogs/covers');
                $storedFiles[] = $data['cover_photo'];
            }

            $blog->update($data);
        } catch (Throwable $exception) {
            $this->deleteStoredFiles($storedFiles);

            throw $exception;
        }

        $this->deleteStoredFiles($oldFiles);

        return $blog;
    }

    public function deleteBlog(Blog $blog): void
    {
        $files = [$blog->thumbnail, $blog->cover_photo];

        $blog->delete();

        $this->deleteStoredFiles($files);
    }

    public function changeStatus(Blog $blog): bool
    {
        return $blog->update(['status' => ! $blog->status]);
    }

    private function storeImage(UploadedFile $image, string $directory): string
    {
        $path = $image->store($directory, 'public');

        if (! is_string($path)) {
            throw new RuntimeException('Unable to store the blog image.');
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
}
