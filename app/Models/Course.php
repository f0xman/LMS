<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use App\Filters\QueryFilter;

class Course extends Model
{
    public function seminars()
    {
        return $this->hasMany(Seminar::class, 'course_id');
    }
}
