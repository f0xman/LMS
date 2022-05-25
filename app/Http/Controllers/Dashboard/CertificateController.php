<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Order;
use App\Models\Course;
use App\Models\Seminar;
use App\Services\CertificateHandler;


class CertificateController extends Controller
{

    public function index(Order $order, Seminar $seminar)
    {
        $orderIds = $order->getUserOrderIds(Auth::id()); /// рефакторинг, выбрать все одним запросом
        return view('dashboard.certificates', ['seminars' => $seminar->getSeminarsByIds($orderIds)]);
    }


     /**
     * Create Certificate
     *
     * @param  Request  $request
     * @return Response
     */
     public function generateCertificate(Request $request, CertificateHandler $handler)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $seminar = Seminar::where('id', $request->get('seminar_id'))
                    ->with(['teacher'])
                    ->first();                   

        return $handler->generateSertificate($seminar, $request->get('name'));
    }

}
