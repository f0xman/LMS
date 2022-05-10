<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Order;
use App\Models\Seminar;
use App\Services\OrderHandler;
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
     * @param  Order  $order
     * @param  OrderHandler  $handler
     * @return Response
     */
    public function index(Request $request, Order $order, OrderHandler $handler)
    {

        $seminar_id = Session::get('seminar_id');
        $user_id = Auth::id();

        //// Проверим, доступен ли уже семинар студенту.
        //// Защита от повторной покупки для кликнувшего купить "НЕавторизованного"
        if ($order->isSeminarAvailable($seminar_id, $user_id)) {
            Session::flush();
            return redirect()->route('showSeminar', ['id' => $seminar_id])->with('success', 'Этот семинар уже доступен для вас');
        }

        $url = $handler->getYakassaURI($seminar_id, $user_id, $request->get('order_id'));

        if ($url) {
            Session::flush();
            return redirect($url);
        } else {
            return abort(500); //// Это временно, тут надо обработчик ошибок ЯК
        }

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
