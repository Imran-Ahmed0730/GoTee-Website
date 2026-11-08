<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $guarded = ['id'];

    public function deptHead()
    {
        return $this->belongsTo(User::class, 'dept_head_id')
            ->withDefault([
                'first_name' => 'N/A',
                'last_name' => '',
                'email' => 'N/A',
                'profile_photo_path' => null,
            ]);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'department_id');
    }
}
