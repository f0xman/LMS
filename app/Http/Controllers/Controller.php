<?php

namespace App\Http\Controllers;

use App\Mail\ErrorMail;
use App\Mail\SuccessOrderMail;
use App\Models\Course;
use App\Models\Order;
use App\Models\User;
use Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Mail;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);}
        );
    }

    /**
     * Send a success mail after course ordering.
     *
     * @param  int course_id, int user_id
     * @return True
     */
    public function finalizeOrder(int $order_id)
    {
        $data = Order::where('id', $order_id)
            ->with(['course', 'users'])
            ->first();

        ///// Данные в шаблон письма
        $email_data = array(
            'name' => $data->users->name,
            'course_id' => $data->course->id,
            'course_title' => $data->course->title,
        );

        Mail::to($data->users->email)->send(new SuccessOrderMail($email_data));

        if ($data->coupon_code != null) {
            $this->flushCoupon($data->coupon_code);
        }

        //Coupon::where('code', $data->coupon_code)->update(['used' => 1]);

        return true;
    }

    /**
     * Send an error mail for me.
     *
     * @param  string data, string from
     * @return True
     */
    public function sendErrorMail(String $data, String $from)
    {
        ///// Данные в шаблон письма
        $email_data = array(
            'from' => $from,
            'error' => $data,
        );

        Mail::to('opexa2@yandex.ru')->send(new ErrorMail($email_data));

        return true;
    }

}
