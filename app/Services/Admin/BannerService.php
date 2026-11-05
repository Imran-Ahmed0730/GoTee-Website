<?php

namespace App\Services\Admin;

use App\Models\Admin\Banner;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use RuntimeException;

class BannerService
{
    /**
     * Create a new class instance.
     */
    public function getAllBanners(array $filters = []): LengthAwarePaginator
    {
        return Banner::query()
            ->when(
                isset($filters['position']) && $filters['position'] !== '',
                fn (Builder $query) => $query->where('position', (int) $filters['position'])
            )
            ->when(
                isset($filters['status']) && $filters['status'] !== '',
                fn (Builder $query) => $query->where('status', (int) $filters['status'])
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();
    }

    public function createBanner(array $data)
    {
        $data['image'] = updateImagePath($data['image'], null, 'banners');

        return Banner::create($data);
    }

    public function updateBanner(Banner $banner, array $data)
    {
        if (isset($data['image'])) {
            $data['image'] = updateImagePath($data['image'], $banner->image, 'banners');
        }

        return $banner->update($data);
    }

    public function deleteBanner(Banner $banner)
    {
        $imagePath = public_path($banner->image);

        if ($banner->image && is_file($imagePath) && ! unlink($imagePath)) {
            throw new RuntimeException('Unable to remove the banner image.');
        }

        return $banner->delete();
    }

    public function changeStatus(Banner $banner)
    {
        $banner->status = $banner->getRawOriginal('status') == 1 ? 0 : 1;
        $banner->save();
    }
}
