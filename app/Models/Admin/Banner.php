<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Banner extends Model
{
    protected $guarded = ['id'];

    /**
     * @return Attribute<string, string|int|bool>
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: static fn ($value): string => (int) $value === 1 ? 'Active' : 'Inactive',
            set: static fn ($value): int => match (strtolower((string) $value)) {
                '1', 'true', 'active' => 1,
                '0', 'false', 'inactive' => 0,
                default => throw new InvalidArgumentException('Banner status must be Active or Inactive.'),
            },
        );
    }

    /**
     * @return Attribute<string, string|int>
     */
    protected function position(): Attribute
    {
        return Attribute::make(
            get: static fn ($value): string => match ((int) $value) {
                1 => 'Top',
                2 => 'Middle',
                3 => 'Bottom',
                default => (string) $value,
            },
            set: static fn ($value): int => match (strtolower((string) $value)) {
                '1', 'top' => 1,
                '2', 'middle' => 2,
                '3', 'bottom' => 3,
                default => throw new InvalidArgumentException('Banner position must be Top, Middle, or Bottom.'),
            },
        );
    }
}
