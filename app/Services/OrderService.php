<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Log;
use App\Models\Seminar;
use Mclass\Yakassa\Yakassa;
use App\Mail\ErrorMail;
use App\Mail\SuccessOrderMail;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Response;

final class OrderService
{

    protected $kassa;

    public function __construct(Yakassa $kassa)
    {
        $this->kassa = $kassa;
    }    

    /**
     * Собираем данные семинара и заказа
     * Формируем урл для отправки данных на Yookassa API
     *
     * @param  Int $orderId - id заказа: null или ранее не оплаченный id
     * @return String URI
     */
    public function composeYakassaURI(Int $seminarId, Int $userId, Int $orderId) : String
    {
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
     *  Получение платежного урл для перехода на юкассу
     *
     * @return String $url
     */
    private function getYakassaURI(Int $order_id, Array $data) : String
    {
        $yookassaResponse = $this->kassa->getYakassaConfirmationUrl($data);

        $this->updataYookassaId($order_id, $yookassaResponse['id']);

        return $yookassaResponse['confirmation']['confirmation_url'];
    }

    /**
     *  Обновим в заказе  yookassa_id
     *
     * @return Void
     */    
    private function updataYookassaId(Int $order_id, String $yookassa_id) : Void
    {
        Order::where('id', $order_id)->update(['yookassa_id' => $yookassa_id]);
    }

    /**
     *  Собираем массив данных, из семинара, для создания заказа 
     *  и отправки на Yookassa API
     *
     * @return Array $orderData
     */
    private function composeSeminarData(Int $seminarId, Int $userId) : Array
    {
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
     * @return Int $orderId
     */
    private function getOrCreateOrderId(Int $orderId, Array $seminarData) : Int
    {
        if ($orderId == 0) {
            $new_order = new Order($seminarData);
            $new_order->save();
            return $new_order->id;
        }
        return $orderId;
    }


    /**
     *  Получение и обоаботка результата оплаты с Yakassa
     *  Изменение статуса платежа
     *
     * @return response
     */
    public function handleYakassaRequest(Array $request) : Response
    {
        $status = $request['object']['status'];
        $yookassa_id = $request['object']['id'];
        $order_id = $request['object']['metadata']['order_id'];

        $order = Order::where('yookassa_id', $yookassa_id)
                        ->where('id', $order_id)
                        ->with(['seminar', 'user'])
                        ->first();

        if (!$order) {
            throw new HttpException(400, "Invalid data");
        }

        if($order->status == 'succeeded')  {
            return response("", 200);
        }  

        if ($status == 'succeeded') {
            $this->updateOrderStatus($order_id, $yookassa_id);
            $this->finalizeOrder($order);
            return response("", 200);
        }

        return response(null, 204);
    }

    /**
     * Обновление статуса заказа, полученного с Yookassa API
     *
     * @return Void
     */
    public function updateOrderStatus(Int $order_id, String $yookassa_id) : Void 
    {
        try {
            Order::where('id', $order_id)
                    ->where('yookassa_id', $yookassa_id)
                    ->update(['status' => 'succeeded', 'level' => 2]);
        }
        catch(\Exception $exception) {
            throw new HttpException(400, "Invalid data updateOrderStatus - {$exception}");
        }
    }

    /**
     * Финализация заказа, отправка письма пользователю о успешной покупке
     *
     * @return Void
     */
    public function finalizeOrder(Order $order) : Void
    {
        $email_data = array(
            'name' => $order->user->name,
            'seminar_id' => $order->seminar->id,
            'seminar_title' => $order->seminar->title,
        );
        
        Mail::to($order->user->email)->send(new SuccessOrderMail($email_data));
    }

}
