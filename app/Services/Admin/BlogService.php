<?php

namespace App\Services\Admin;

use App\Models\Admin\Blog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
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
        $storedImages = [];

        try {
            $data['thumbnail'] = updateImagePath($data['thumbnail'], null, 'blogs/thumbnails');
            $storedImages[] = $data['thumbnail'];

            if (($data['cover_photo'] ?? null) instanceof UploadedFile) {
                $data['cover_photo'] = updateImagePath($data['cover_photo'], null, 'blogs/covers');
                $storedImages[] = $data['cover_photo'];
            }

            return Blog::create($data);
        } catch (Throwable $exception) {
            $this->deleteImages($storedImages);

            throw $exception;
        }
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateBlog(Blog $blog, array $data): Blog
    {
        $oldImages = [];
        $storedImages = [];

        try {
            if (($data['thumbnail'] ?? null) instanceof UploadedFile) {
                $oldImages[] = $blog->thumbnail;
                $data['thumbnail'] = updateImagePath($data['thumbnail'], null, 'blogs/thumbnails');
                $storedImages[] = $data['thumbnail'];
            }

            if (($data['cover_photo'] ?? null) instanceof UploadedFile) {
                $oldImages[] = $blog->cover_photo;
                $data['cover_photo'] = updateImagePath($data['cover_photo'], null, 'blogs/covers');
                $storedImages[] = $data['cover_photo'];
            }

            $blog->update($data);
        } catch (Throwable $exception) {
            $this->deleteImages($storedImages);

            throw $exception;
        }

        $this->deleteImages($oldImages);

        return $blog;
    }

    public function deleteBlog(Blog $blog): void
    {
        $images = [$blog->thumbnail, $blog->cover_photo];

        $blog->delete();

        $this->deleteImages($images);
    }

    public function changeStatus(Blog $blog): bool
    {
        return $blog->update(['status' => ! $blog->status]);
    }

    private function deleteImage(?string $path): void
    {
        if ($path !== null && is_file(public_path($path)) && ! unlink(public_path($path))) {
            throw new RuntimeException('Unable to remove a blog image.');
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
