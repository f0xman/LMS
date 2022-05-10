<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Seminar;
use Illuminate\Support\Facades\Auth;
use Mclass\Yakassa\Yakassa;

final class OrderHandler
{

    protected $kassa;

    public function __construct(Yakassa $kassa)
    {
        $this->kassa = $kassa;
    }
    

    /**
     * Формируем урл для отправки данных на Yookassa API
     *
     * @param  Int $seminarId - id семинара
     * @param  Int $userId - id пользователя
     * @param  Int $orderId - id заказа: null или ранее не оплаченный id
     * @return String URI
     */
    public function getYakassaURI(Int $seminarId, Int $userId, Int $orderId) {

        $order_data = $this->composeSeminarData($seminarId, $userId);

        $seminarTitle = $order_data['seminar_title'];
        unset($order_data['seminar_title']);
        
        $order_id = $this->getOrCreateOrderId($orderId, $order_data);

        ///// Данные для отправки на yookassa
        $yookassa_data = array('order_id' => $order_id,
            'amount' => $order_data['price'],
            'description' => 'Заказ № ' . $order_id . '. ' . $seminarTitle
        );

        $orderData = array_merge($order_data, $yookassa_data);


        return $this->retrieveYakassaURI($order_id, $orderData, $this->kassa);

    }

    /**
     *  Отправка данных на Yakassa
     *  Обновление yookassa_id для заказа
     *  Получение платежного урл для перехода на юкассу
     *
     * @param  Int  $order_id
     * @param  array  $data
     * @return string $url
     */
    private function retrieveYakassaURI(Int $order_id, Array $data)
    {
        $yookassaResponse = $this->kassa->getYakassaConfirmationUrl($data);

        Order::where('id', $order_id)
            ->update(['yookassa_id' => $yookassaResponse['id']]);

        return $yookassaResponse['confirmation']['confirmation_url'];
    }

    /**
     *  Собираем массив данных, из семинара, для создания заказа 
     *  и отправки на Yookassa API
     *
     * @param int $seminarId
     * @param int $userId
     * @return array $orderData
     */
    private function composeSeminarData(Int $seminarId, Int $userId) {

        $seminar = Seminar::select('price', 'title', 'date')->where('id', $seminarId)->first();

        $time = strtotime($seminar->date);
        $open_to = date("Y-m-d", strtotime("+1 month", $time)); /// Дата, до которой будет доступен семинар в ЛК

        $orderData = array('seminar_id' => $seminarId,
            'user_id' => $userId,
            'price' => $seminar->price,
            'open_to' => $open_to,
            'seminar_title' => $seminar->title
        );

        return $orderData;
    }

    /**
     *  Проверка id заказа (ранее не оплаченный)
     *  Если нет, заводим новый заказ и возвращаем его id
     *
     * @param int $orderId
     * @param array $seminarData
     * @return int $orderId
     */
    private function getOrCreateOrderId(Int $orderId, Array $seminarData)
    {
        if ($orderId == null) {
            $new_order = new Order($seminarData);
            $new_order->save();
            return $new_order->id;
        }
        return $orderId;
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
