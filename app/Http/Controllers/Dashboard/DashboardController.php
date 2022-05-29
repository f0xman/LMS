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
     * Мягкое удаление семинара
     *
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

}
