<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $fillable = ['seminar_id', 'user_id', 'payed', 'price'];

    public function seminar()
    {
        return $this->belongsTo(Seminar::class)->withDefault();
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    //// Куплен ли уже семинар или неоплачен
    public function isAlreadyPurchased($seminarId, $userId)
    {
        $result = self::select('id', 'status')
            ->where('removed', 0)
            ->where('user_id', $userId)
            ->where('seminar_id', $seminarId)
            ->first();
        if ($result) { 
            return ( $result->status == 'succeeded' ) ? true : $result->id;
        }
        return null;
    }

    //// Доступен ли семинар ученику
    public function isSeminarAvailable($seminarId, $userId)
    {
        $query = self::select('id', 'level')
            ->where('status', 'succeeded')
            ->whereDate('open_to', '>', date("Y-m-d"))
            ->where('user_id', $userId)
            ->where('seminar_id', $seminarId);

        return $query->first();
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}
