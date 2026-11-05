<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $guarded = ['id'];

    public function deptHead()
    {
        return $this->belongsTo(User::class, 'dept_head_id')
            ->withDefault([
                'name' => '-'
            ]);
    }
}
