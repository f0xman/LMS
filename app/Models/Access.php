<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Access extends Model
{

    protected $table = 'access_levels';

    protected $fillable = ['title'];

}
