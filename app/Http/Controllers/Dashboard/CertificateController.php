<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Order;
use App\Models\Course;
use App\Models\Seminar;
use App\Services\CertificateService;


class CertificateController extends Controller
{

    public function index(Order $order, Seminar $seminar)
    {
        $orderIds = $order->getUserOrderIds(Auth::id()); 
        return view('dashboard.certificates', ['seminars' => $seminar->getSeminarsByIds($orderIds)]);
    }

     /**
     * Генерация сертификата
     *
     * @return Response
     */
     public function generateCertificate(Request $request, CertificateService $service)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $seminar = Seminar::where('id', $request->get('seminar_id'))
                    ->with(['teacher'])
                    ->first();                   

        return $service->generateSertificate($seminar, $request->get('name'));
    }

}
