<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Order;
use App\Models\Course;
use App\Models\Seminar;
use App\Services\CertificateHandler;
use Imagick;

class CertificateController extends Controller
{

    public function index()
    {
        $orderIds = Order::where('user_id', Auth::id())
                            ->where('status', 'succeeded')
                            ->pluck('seminar_id')->toArray();

        $seminar = Seminar::whereIn('id', $orderIds)->get();

        return view('dashboard.certificates', ['seminars' => $seminar]);
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
