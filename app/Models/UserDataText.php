<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDataText extends Model
{
    protected $fillable = [
        'form_id',
        'form_field_id',
        'user_id',
        'value',
        'updated_at',
    ];
}

