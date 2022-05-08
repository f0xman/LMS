<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Comment extends Model
{
    protected $fillable = ['lesson_id', 'user_id', 'comment', 'parent_id'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

     public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'parent_id');

    }
}
