<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMapping extends Model
{
    protected $fillable = [
        'user_id',
        'model_user_id'
    ];
}
