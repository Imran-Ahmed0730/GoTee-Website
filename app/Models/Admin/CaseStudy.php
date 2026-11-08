<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseStudy extends Model
{
    protected $guarded = ['id'];

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

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class)->withDefault([
            'name' => 'Unknown client',
        ]);
    }

    public function testimonial(): BelongsTo
    {
        return $this->belongsTo(Testimonial::class);
    }
}
