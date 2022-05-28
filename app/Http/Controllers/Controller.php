<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Текущий уровень доступа пользователя в контексте купленного семинара!!! Заказа
     * По умолчанию - 1, это уровень без оплаченного заказа
     * Берется из поля заказа | level
     *
     * @var Int
     */
    protected $currentUserLevel = 1;

    /**
     * @var Order
     */
    protected $order;

    public function __construct(Order $order) {
        $this->order = $order;
    }

    /**
     * Проверка, доступен ли семинар пользователю
     *
     * @param  Int $seminar_id
     * @return Bool
     */
    public function isContentAvailable(Int $seminar_id) : Bool
    {
       
        $payedOrder = $this->order->isOrderPayed($seminar_id, Auth::id());

        if($payedOrder) { 
            $this->setCurrentUserLevel($payedOrder->level);           
            return true;
        }

        return false;
    }

    private function setCurrentUserLevel(Int $levelNum) : Void
    {
        $this->currentUserLevel = $levelNum;
    }

}
