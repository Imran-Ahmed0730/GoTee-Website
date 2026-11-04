<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class BlogCategory extends Model
{
    protected $guarded = ['id'];

    protected static function booted(): void
    {
        static::saving(function (self $category): void {
            if (! $category->exists || $category->isDirty('name')) {
                $baseSlug = Str::slug($category->name) ?: 'category';
                $slug = $baseSlug;
                $suffix = 2;

                while (static::query()
                    ->where('slug', $slug)
                    ->where('id', '!=', $category->getKey() ?? 0)
                    ->exists()) {
                    $slug = $baseSlug.'-'.$suffix++;
                }

                $category->slug = $slug;
            }
        });
    }

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'category_id');
    }
}
