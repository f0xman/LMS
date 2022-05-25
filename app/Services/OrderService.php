<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Log;
use App\Models\Seminar;
use Mclass\Yakassa\Yakassa;
use App\Mail\ErrorMail;
use App\Mail\SuccessOrderMail;
use Illuminate\Support\Facades\Mail;

final class OrderService
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
    public function composeYakassaURI(Int $seminarId, Int $userId, Int $orderId) {

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

        return $this->getYakassaURI($order_id, $orderData, $this->kassa);

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
    private function getYakassaURI(Int $order_id, Array $data)
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
        if ($orderId == 0) {
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
    // public function updateOrderStatus(String $orderId) : String 
    // {

    //     //// Получим текущий yookassa_id заказа
    //     $order = Order::select('yookassa_id', 'status')->where('id', $orderId)->first();

    //     if ($order->status != 'succeeded') {

    //         $yookassaResponseStatus = $kassa->getYakassaStatus($order->yookassa_id);

    //         if ($yookassaResponseStatus) {
    //             Order::where('id', $order_id)->update(['status' => $yookassaResponseStatus]);
    //         }

    //         return $yookassaResponseStatus;
    //     }

    //     return "succeeded";
    // }

    /**
     *  Получение и обоаботка результата оплаты с Yakassa
     *  Изменение статуса платежа
     *
     * @param  Request $request
     * @return response
     */
    public function handleYakassaRequest(Request $request) : void
    {

        $status = $request->input('object.status');
        $yookassa_id = $request->input('object.id');
        $order_id = $request->input('object.metadata.order_id');
        $seminar_id = $request->input('object.metadata.seminar_id');
        $user_id = $request->input('object.metadata.user_id');

        $order = Order::where('yookassa_id', $yookassa_id)
                        ->where('id', $order_id)
                        ->with(['seminar', 'user'])
                        ->first();

        if($order->status == 'succeeded')  {
            die();
        }  

        if ($status == 'succeeded') {
            Order::where('id', $order_id)
                ->where('yookassa_id', $yookassa_id)
                ->update(['status' => $status, 'level' => 2]);

            $this->finalizeOrder($order_id);
        }

    }

    /**
     * Send a success mail after ordering.
     *
     * @param  int course_id, int user_id
     * @return void
     */
    public function finalizeOrder(Order $order) : void
    {
        $email_data = array(
            'name' => $order->users->name,
            'seminar_id' => $order->seminar->id,
            'seminar_title' => $order->seminar->title,
        );
        
        Mail::to($data->users->email)->send(new SuccessOrderMail($email_data));
    }

}
