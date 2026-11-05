<?php

namespace Tests\Feature\Admin;

use App\Models\Admin\Banner;
use App\Services\Admin\BannerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BannerModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_status_and_position_accessors_format_values_and_mutators_store_codes(): void
    {
        $banner = Banner::create([
            'image' => 'uploads/banners/banner.png',
            'position' => 'Middle',
            'status' => 'Inactive',
        ]);

        $this->assertSame('Middle', $banner->position);
        $this->assertSame('Inactive', $banner->status);
        $this->assertDatabaseHas('banners', [
            'id' => $banner->id,
            'position' => 2,
            'status' => 0,
        ]);

        $banner->position = 'Bottom';
        $banner->status = 'Active';
        $banner->save();
        $banner->refresh();

        $this->assertSame('Bottom', $banner->position);
        $this->assertSame('Active', $banner->status);
        $this->assertDatabaseHas('banners', [
            'id' => $banner->id,
            'position' => 3,
            'status' => 1,
        ]);

        app(BannerService::class)->changeStatus($banner);
        $banner->refresh();

        $this->assertSame('Inactive', $banner->status);
        $this->assertDatabaseHas('banners', [
            'id' => $banner->id,
            'status' => 0,
        ]);
    }
}
