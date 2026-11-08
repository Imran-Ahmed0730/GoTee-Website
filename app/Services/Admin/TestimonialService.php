<?php

namespace App\Services\Admin;

use App\Models\Admin\Testimonial;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use RuntimeException;
use Throwable;

class TestimonialService
{
    public function getTestimonials(?string $search = null, ?string $status = null): LengthAwarePaginator
    {
        return Testimonial::query()
            ->when($search !== null && $search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('client_name', 'like', '%'.$search.'%')
                        ->orWhere('first_name', 'like', '%'.$search.'%')
                        ->orWhere('last_name', 'like', '%'.$search.'%')
                        ->orWhere('designation', 'like', '%'.$search.'%')
                        ->orWhere('comment', 'like', '%'.$search.'%');
                });
            })
            ->when(in_array($status, ['0', '1'], true), fn (Builder $query): Builder => $query->where('status', (int) $status))
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function createTestimonial(array $data): Testimonial
    {
        $imagePath = null;

        try {
            if (($data['image'] ?? null) !== null) {
                $imagePath = updateImagePath($data['image'], null, 'testimonials');
                $data['image'] = $imagePath;
            }

            return Testimonial::create($data);
        } catch (Throwable $exception) {
            $this->deleteImage($imagePath);

            throw $exception;
        }
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateTestimonial(Testimonial $testimonial, array $data): Testimonial
    {
        $newImagePath = null;
        $oldImagePath = $testimonial->image;

        try {
            if (($data['image'] ?? null) !== null) {
                $newImagePath = updateImagePath($data['image'], null, 'testimonials');
                $data['image'] = $newImagePath;
            } else {
                unset($data['image']);
            }

            $testimonial->update($data);
        } catch (Throwable $exception) {
            $this->deleteImage($newImagePath);

            throw $exception;
        }

        if ($newImagePath !== null) {
            $this->deleteImage($oldImagePath);
        }

        return $testimonial->refresh();
    }

    public function deleteTestimonial(Testimonial $testimonial): void
    {
        $imagePath = $testimonial->image;
        $testimonial->delete();
        $this->deleteImage($imagePath);
    }

    public function changeStatus(Testimonial $testimonial): Testimonial
    {
        $testimonial->update(['status' => ! $testimonial->getRawOriginal('status')]);

        return $testimonial->refresh();
    }

    private function deleteImage(?string $path): void
    {
        if ($path === null || ! str_starts_with($path, 'uploads/testimonials/')) {
            return;
        }

        $absolutePath = public_path($path);

        if (is_file($absolutePath) && ! unlink($absolutePath)) {
            throw new RuntimeException('Unable to remove the testimonial image.');
        }
    }
}
