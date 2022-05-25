<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use App\Filters\QueryFilter;

class Seminar extends Model
{

    public function course()
    {
        return $this->belongsTo(Course::class)->withDefault();
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class, 'seminar_id');
    }

    public function files()
    {
        return $this->hasMany(File::class, 'seminar_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'seminar_id');
    }    

    public function scopeFilter(Builder $builder, QueryFilter $filters)
    {
        return $filters->apply($builder);
    }

    public function isTeacherCourse($courseId, $teacherId)
    {
        return $this->where('teacher_id', $teacherId)
                    ->where('id', $courseId)
                    ->exists();
    }

    public function getSeminarsByIds(Array $ids) {
        return self::whereIn('id', $ids)->get();
    } 

}
