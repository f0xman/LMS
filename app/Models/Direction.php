<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Direction extends Model
{
    public function courses()
    {
        return $this->hasMany(Course::class, 'direction_id');
    }
}
