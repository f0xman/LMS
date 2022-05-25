<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index(Order $order)
    {
        return view('dashboard.index', ['seminarsAvailable' => $order->getAvailableSeminars(Auth::id()),
                                        'seminarsNotAvailable' => $order->getNotAvailableSeminars(Auth::id()) 
                                        ]);
    }

    /**
     * Delete a seminar
     *
     * @param  Request $request
     * @return Response
     */
    public function deleteSeminar(Request $request)
    {
        $result = Order::where('id', $request->get('order_id'))
                    ->where('user_id', Auth::id())
                    ->update(['removed' => 1]);

        return ($result) ?
        redirect()->route('dashboard')->with('success', 'Семинар удален') :
        redirect()->route('dashboard')->with('error', 'Не удалось удалить семинар') ;
    }

    /**
     * Success page after ordering. ЭТО ВРЕМЕННЫЙ КОСТЫЛЬ для проверки ответа !!!!
     *
     * @param  string $order_id, OrderHandler $order
     * @return view result
     */
    // public function success(string $order_id, OrderService $order)
    // {
        
    //     $yookassaResponseStatus = $order->updateOrderStatus($order_id);

    //     if ($yookassaResponseStatus == 'succeeded') {
            
    //         $result = 'success';

    //         if ($order->status != 'succeeded') {
    //             $order->finalizeOrder($order_id);
    //         }

    //     } else {
    //         $result = 'error'; //// TODO error меня напрягает, надо что-то придумать
    //     }

    //     return view('dashboard.success', ['result' => $result]);
    // }

}
