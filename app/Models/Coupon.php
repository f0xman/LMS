<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['percent', 'user_id', 'used', 'code'];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

}
