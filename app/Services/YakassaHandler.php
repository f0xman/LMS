<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

final class YakassaHandler
{

    protected $kassa;

    public function __construct(Yakassa $kassa)
    {
        $this->kassa = $kassa;
    }
    
    /**
     * Обновление статуса заказа, полученного с Yookassa API
     *
     * @param  string $orderId
     * @return String status
     */
    public function updateOrderStatus(String $orderId)  {

        //// Получим текущий yookassa_id заказа
        $order = Order::select('yookassa_id', 'status')->where('id', $orderId)->first();

        if ($order->status != 'succeeded') {

            $yookassaResponseStatus = $kassa->getYakassaStatus($order->yookassa_id);

            if ($yookassaResponseStatus) {
                Order::where('id', $order_id)->update(['status' => $yookassaResponseStatus]);
            }

            return $yookassaResponseStatus;
        }

        return "succeeded";
    }

}
