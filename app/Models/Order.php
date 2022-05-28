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

    public function user()
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

    //// Оплачен ли семинар? Доступен ли семинар ученику
    public function isOrderPayed($seminarId, $userId)
    {
        return self::select('id', 'level')
                    ->where('status', 'succeeded')
                    ->whereDate('open_to', '>', date("Y-m-d"))
                    ->where('user_id', $userId)
                    ->where('seminar_id', $seminarId)
                    ->first();
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getUserOrderIds(Int $userId): Array {
        return self::where('user_id', $userId)
                    ->where('status', 'succeeded')
                    ->pluck('seminar_id')->toArray();
    } 

    public function getNotAvailableSeminars(Int $userId) {
        return self::where('user_id', $userId)
                    ->where('removed', '0')
                    ->where(function ($query) {
                        $query->where('status', '!=', 'succeeded')
                            ->orWhere('status', null);
                    })
                    ->orderBy('id', 'Desc')
                    ->with(['seminar'])
                    ->get();
    } 

    public function getAvailableSeminars(Int $userId) {
        return self::where('user_id', $userId)
                    ->where('status', 'succeeded')
                    ->orderBy('id', 'Desc')
                    ->with(['seminar'])
                    ->get();
    } 

    public function getOrderIdBySeminarId(Int $seminarId, Int $userId) : Int
    {
        return self::select('id')
                    ->where('user_id', $userId)
                    ->where('seminar_id', $seminarId)
                    ->value('id');
    }

}
