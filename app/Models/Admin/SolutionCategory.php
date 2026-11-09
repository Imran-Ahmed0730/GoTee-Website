<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SolutionCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'image',
        'icon',
        'description',
        'status',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $category): void {
            if (! $category->exists || $category->isDirty('name')) {
                $baseSlug = Str::substr(Str::slug($category->name) ?: 'solution-category', 0, 255);
                $slug = $baseSlug;
                $suffix = 2;

                while (static::query()
                    ->where('slug', $slug)
                    ->where('id', '!=', $category->getKey() ?? 0)
                    ->exists()) {
                    $suffixText = '-'.$suffix++;
                    $slug = Str::substr($baseSlug, 0, 255 - strlen($suffixText)).$suffixText;
                }

                $category->slug = $slug;
            }
        });
    }
}
