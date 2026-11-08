<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Testimonial extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
        ];
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: static fn ($value): string => (int) $value === 1 ? 'Show' : 'Hidden',
            set: static fn ($value): mixed => match (is_string($value) ? strtolower($value) : $value) {
                true, 1, '1', 'show' => 1,
                false, 0, '0', 'hidden' => 0,
                default => $value,
            },
        );
    }

    public function caseStudies(): HasMany
    {
        return $this->hasMany(CaseStudy::class);
    }
}
