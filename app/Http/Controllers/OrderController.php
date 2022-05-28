<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Seminar;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    protected $service;

    public function __construct(OrderService $service) {
        $this->service = $service;
    }

    /**
     * Создание нового заказа
     * АВТОРИЗОВАННЫЙ посетитель нажавший "купить курс""
     *
     * @param  Request  $request
     * @param  Order  $order
     * @return Response|View
     */
    public function create(Request $request, Order $order) 
    {
        $seminar_id = $request->session()->get('seminar_id'); 
        $user_id = Auth::id();
        $order_id = ( $request->get('order_id') != null) ? $request->get('order_id') : 0 ;

        //// Проверим, доступен ли уже семинар студенту.
        //// Защита от повторной покупки для кликнувшего купить "НЕавторизованного"
        $purchased = $order->isAlreadyPurchased($seminar_id, $user_id);
        
        if ($purchased != null) {
            if (is_int($purchased)) { // Курс куплен, но не оплачен
                $order_id = $purchased;
            } else { // Уже куплен и доступен
                $request->session()->flush();
                return redirect()->route('showSeminar', ['id' => $seminar_id])->with('success', 'Этот семинар уже доступен для вас');
            }
        }

        $url = $this->service->composeYakassaURI($seminar_id, $user_id, $order_id);

        if ($url) {
            $request->session()->forget(['toPaymentGate', 'seminar_id']);
            return redirect($url);
        } else {
            //return abort(500); //// Это временно, тут надо обработчик ошибок ЯК
            return view('errors.yakassa');
        }
    }

    /**
     *  Обработка полученного с Yakassa уведомления о статусе платежа 
     *  ЭТО НАДО ВЫНЕСТИ В АПИ
     *
     * @param  Request $request
     * @return response
     */
    // public function notifier(Request $request)
    // {
    //     return $this->service->handleYakassaResponce($request);
    // }

}
