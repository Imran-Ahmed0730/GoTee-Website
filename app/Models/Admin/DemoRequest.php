<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class DemoRequest extends Model
{
    protected $fillable = [
        'company',
        'contacting_person_first_name',
        'contacting_person_last_name',
        'email',
        'phone',
        'address',
        'machine_qty',
    ];
}
