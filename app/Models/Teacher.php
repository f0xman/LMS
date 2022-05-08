<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    public function users()
    {
        return $this->hasOne(User::class, 'teacher_id');
    }

}
