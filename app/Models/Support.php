<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Support extends Model
{

    protected $table = 'support';

    protected $fillable = ['title', 'user_id'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getSupportById ($id, $userId)
    {
        return $this->select('id','title')
                    ->where('id', $id)
                    ->where('user_id', $userId)
                    ->first();
    }

    public function supportmessages()
    {
        return $this->hasMany(SupportMessage::class, 'support_id');
    }

    public function supportnestedmessages()
    {
        return $this->hasMany(SupportMessage::class, 'support_id')->where('admin', -1);
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();

    }
}
