<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class SupportMessage extends Model
{

    protected $table = 'support_messages';

    protected $fillable = ['user_id', 'message', 'admin', 'unread', 'support_id'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}
