<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index()
    {
        $seminarsNotAvailable = Order::where('user_id', Auth::id())
            ->where('removed', '0')
            ->where(function ($query) {
                $query->where('status', '!=', 'succeeded')
                    ->orWhere('status', null);
            })
            ->orderBy('id', 'Desc')
            ->with(['seminar'])
            ->get();

        $seminarsAvailable = Order::where('user_id', Auth::id())
            ->where('status', 'succeeded')
            ->orderBy('id', 'Desc')
            ->with(['seminar'])
            ->get();

        return view('dashboard.index', ['seminarsAvailable' => $seminarsAvailable,
            'seminarsNotAvailable' => $seminarsNotAvailable
        ]);
    }

    /**
     * Delete a seminar
     *
     * @param  Request  $request
     * @return Response
     */
    public function deleteSeminar(Request $request)
    {
        $res = Order::where('id', $request->get('order_id'))
                    ->where('user_id', Auth::id())
                    ->update(['removed' => 1]);

        return ($res) ?
        redirect()->route('dashboard')->with('success', 'Семинар удален') :
        redirect()->route('dashboard')->with('error', 'Не удалось удалить семинар') ;
    }

    /**
     * Success page after ordering.
     *
     * @param  string $order_id, OrderHandler $order
     * @return view result
     */
    public function success(string $order_id, OrderHandler $order)
    {
        
        $yookassaResponseStatus = $order->updateOrderStatus($order_id);

        if ($yookassaResponseStatus == 'succeeded') {
            
            $result = 'success';

            if ($order->status != 'succeeded') {
                $this->finalizeOrder($order_id);
            }

        } else {
            $result = 'error'; //// TODO error меня напрягает, надо что-то придумать
        }

        return view('dashboard.success', ['result' => $result]);
    }

}
