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

    
    // public function setImagesAttribute($images)
    // {
    //     if (is_array($images)) {
    //     $this->attributes['images'] = json_encode($images);
    //     }
    // }


    // public function getImagesAttribute($images)
    // {
    //     return json_decode($images, true);
    // }

    public static function isTeacherCourse($courseId, $teacherId)
    {
        return static::where('teacher_id', $teacherId)
                    ->where('id', $courseId)
                    ->exists();
    }

}
