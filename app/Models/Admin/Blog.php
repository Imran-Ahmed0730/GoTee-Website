<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $guarded = ['id'];

    protected static function booted(): void
    {
        static::saving(function (self $blog): void {
            if (! $blog->exists || $blog->isDirty('title')) {
                $baseSlug = Str::slug($blog->title) ?: 'blog';
                $slug = $baseSlug;
                $suffix = 2;

                while (static::query()
                    ->where('slug', $slug)
                    ->where('id', '!=', $blog->getKey() ?? 0)
                    ->exists()) {
                    $slug = $baseSlug.'-'.$suffix++;
                }

                $blog->slug = $slug;
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'category_id')->withDefault([
            'name' => 'Unknown',
        ]);
    }
}
