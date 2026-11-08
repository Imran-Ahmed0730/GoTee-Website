<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'gender' => 'integer',
            'joining_date' => 'date',
            'salary' => 'decimal:2',
            'show_as_team' => 'boolean',
        ];
    }

    public function status(): Attribute
    {
        return Attribute::make(
            get: static fn ($value): string => (int) $value === 1 ? 'Active' : 'Inactive',
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'first_name' => 'N/A',
            'last_name' => '',
            'email' => 'N/A',
            'profile_photo_path' => null,
        ]);
    }

    public function bloodGroup(): BelongsTo
    {
        return $this->belongsTo(BloodGroup::class)->withDefault([
            'name' => 'N/A',
        ]);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class)->withDefault([
            'name' => 'N/A',
        ]);
    }

    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class)->withDefault([
            'name' => 'N/A',
        ]);
    }

    public function reportingManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporting_manager_id')->withDefault([
            'first_name' => 'N/A',
            'last_name' => '',
            'email' => 'N/A',
            'profile_photo_path' => null,
        ]);
    }
}
