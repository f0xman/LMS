<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promocode extends Model
{
    protected $fillable = ['percent', 'code', 'start', 'end', 'off', 'courses'];

    public function getCoursesAttribute($courses)
    {
        return explode(',', $courses);
    }

    public function setCoursesAttribute($courses)
    {
        $this->attributes['courses'] = implode(',', $courses);
    }

}
