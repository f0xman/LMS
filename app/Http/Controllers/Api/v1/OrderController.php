<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{    
    /**
     * callback Оповещение от Юкассы
     * Обработка запроса со статусом заказа 
     * Прилетает каждый раз при смене статуса
     *
     * @return JsonResponse - Якасса просит 200 ответ в мануале
     */
    public function notify(YakassaOrderRequest $request, OrderService $service) : JsonResponce 
    {
        $this->$service->handleYakassaRequest($request->all());
        return response()->json(null, 200);
    }
}
