<?php

namespace App\Services\Admin;

use App\Models\Admin\Setting;
use Illuminate\Database\Eloquent\Collection;

class SettingService
{
    /**
     * @return Collection<int, Setting>
     */
    public function getAllSettings(): Collection
    {
        return Setting::query()->orderBy('key')->get();
    }

    /**
     * @param  array<int, string>  $keys
     * @return array<string, string|null>
     */
    public function getSettings(array $keys): array
    {
        return Setting::query()
            ->whereIn('key', $keys)
            ->pluck('value', 'key')
            ->all();
    }

    /**
     * @param  array<string, string|null>  $settings
     */
    public function updateSettings(array $settings): void
    {
        foreach ($settings as $key => $value) {
            Setting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $value],
            );
        }
    }
}
