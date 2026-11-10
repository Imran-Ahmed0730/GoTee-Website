<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'status',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $page): void {
            if (! $page->exists || $page->isDirty('title')) {
                $baseSlug = Str::slug($page->title) ?: 'page';
                $slug = $baseSlug;
                $suffix = 2;

                while (static::query()
                    ->where('slug', $slug)
                    ->where('id', '!=', $page->getKey() ?? 0)
                    ->exists()) {
                    $ending = '-'.$suffix++;
                    $slug = substr($baseSlug, 0, 255 - strlen($ending)).$ending;
                }

                $page->slug = $slug;
            }
        });
    }
}
