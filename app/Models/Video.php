<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'videos';

    protected $fillable = ['title', 'video', 'level'];

    public function comments()
    {
        return $this->hasMany(Comment::class, 'video_id')->orderBy('id', 'DESC');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'video_id');
    }

    public function seminar()
    {
        return $this->belongsTo(Seminar::class, 'seminar_id');
    }

}
