<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Order;
use App\Models\Seminar;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Mclass\Yakassa\Yakassa;
use Session;

class OrderController extends Controller
{
    /**
     *  АВТОРИЗОВАННЫЙ посетитель нажавший "купить курс""
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request, Yakassa $kassa, Order $order)
    {

        $seminar_id = Session::get('seminar_id');
        $user_id = Auth::id();

        //// Проверим, доступен ли уже семинар студенту.
        //// Защита от повторной покупки для кликнувшего купить "НЕавторизованного"
        if ($order->isSeminarAvailable($seminar_id, $user_id)) {
            Session::flush();
            return redirect()->route('showCourse', ['id' => $seminar_id])->with('success', 'Этот семинар уже доступен для вас');
        }

        $seminar = Seminar::select('price', 'title', 'date')->where('id', $seminar_id)->first();

        $time = strtotime($seminar->date);
        $open_to = date("Y-m-d", strtotime("+1 month", $time)); /// Дата, до которой будет доступен семинар в ЛК

        $order_data = array('seminar_id' => $seminar_id,
            'user_id' => $user_id,
            'price' => $seminar->price,
            'open_to' => $open_to,
        );

        $order_id = $this->isOrderIdExist($request->get('order_id'), $order_data);

        ///// Данные для отправки на yookassa
        $yookassa_data = array('order_id' => $order_id,
            'amount' => $seminar->price,
            'description' => 'Заказ № ' . $order_id . '. ' . $seminar->title,
        );

        $order_data = array_merge($order_data, $yookassa_data);

        $url = $this->getYakassaURI($order_id, $order_data, $kassa);

        return ($url) ? redirect($url) : abort(500);
    }

    /**
     *  Проверка id заказа (ранее не оплаченный)
     *  Если нет, заводим новый заказ и возвращаем его id
     *
     * @param int $id
     * @param array $data
     * @return int $id
     */
    private function isOrderIdExist($id, $data)
    {
        if ($id == null) {
            $new_order = new Order($data);
            $new_order->save();
            return $new_order->id;
        }
        return $id;
    }

    /**
     *  Отправка данных на Yakassa
     *  Обновление yookassa_id для заказа
     *  Получение платежного урл для перехода на юкассу
     *
     * @param  array  $data
     * @return string $url
     */
    private function getYakassaURI($order_id, $data, $kassa)
    {

        $yookassaResponse = $kassa->getYakassaConfirmationUrl($data);

        Order::where('id', $order_id)
            ->update(['yookassa_id' => $yookassaResponse['id']]);

        Session::flush();

        return $yookassaResponse['confirmation']['confirmation_url'];
    }

    /**
     *  Получение результата оплаты с Yakassa
     *  Изменение статуса платежа
     *
     * @param  Request $request
     * @return response
     */
    public function notifier(Request $request)
    {

        if ($request->input('object.id') != '') {

            $status = $request->input('object.status');
            $yookassa_id = $request->input('object.id');
            $order_id = $request->input('object.metadata.order_id');
            $seminar_id = $request->input('object.metadata.seminar_id');
            $user_id = $request->input('object.metadata.user_id');

            $order = Order::select('status')
                ->where('yookassa_id', $yookassa_id)
                ->where('id', $order_id)
                ->first();

            if ($status == 'succeeded' && $order->status == null) {

                $logData = $yookassa_id . '-' . $status . '-' . $seminar_id . '-' . $order_id;

                Log::create(['body' => $logData, 'ip' => $request->ip()]);

                Order::where('id', $order_id)
                    ->where('yookassa_id', $yookassa_id)
                    ->update(['status' => $status, 'level' => 2]);

                try {
                    $this->finalizeOrder($order_id);
                } catch (\Exception$e) {
                    Log::create(['body' => $e]);
                    $this->sendErrorMail($e, 'notifier');
                    return response('', 200);
                }
            }

        }

        return response('', 200);
    }

}
