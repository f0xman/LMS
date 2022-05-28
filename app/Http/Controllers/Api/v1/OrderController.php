<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\YakassaOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\Response;

class OrderController extends Controller
{    
    /**
     * callback Оповещение от Юкассы
     * Обработка запроса со статусом заказа 
     * Прилетает каждый раз при смене статуса
     * Якасса просит 200-й ответ. Пока не получит 200 - будет слать уведомления
     *
     * @return Response 
     */
    public function notify(YakassaOrderRequest $request, OrderService $service) : Response 
    {
        return $service->handleYakassaRequest($request->all());
    }
}
