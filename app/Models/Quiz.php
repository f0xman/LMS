<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Quiz extends Model
{

    protected $table = 'quiz';

    public $timestamps = false;

    public function questions()
    {
        return $this->hasMany(Question::class, 'quiz_id');
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function quiz_passed()
    {
        return $this->hasOne(QuizPassed::class, 'quiz_id')->where('user_id', Auth::id());
    }
}
