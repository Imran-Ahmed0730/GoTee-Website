<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $guarded = ['id'];

    public function status(): Attribute
    {
        return Attribute::make(
            get: static fn ($value): string => (int) $value === 1 ? 'Active' : 'Inactive',
        );
    }
}
