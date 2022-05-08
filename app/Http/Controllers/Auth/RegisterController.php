<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Mail\WelcomeMail;
use App\Models\User;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['required', 'string'],

            // jur fields
            'jaddress' => ['required', 'string'],
            'inn' => ['required', 'numeric', 'max:12'],
            'kpp' => ['required', 'numeric', 'max:9'],
            'rs' => ['required', 'numeric', 'max:20'],
            'bank' => ['required', 'string'],
            'bic' => ['required', 'numeric', 'max:9'],
            'ks' => ['required', 'numeric', 'max:20'],
            'dn' => ['required', 'string']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {

        ///// Пошлем письмо велком
        $email_data = array(
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        );

        Mail::to($data['email'])->send(new WelcomeMail($email_data));

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'ip' =>  request()->ip(), 

            'jaddress' => $data['jaddress'],
            'inn' => $data['inn'],
            'kpp' => $data['kpp'],
            'rs' => $data['rs'],
            'bank' => $data['bank'],
            'bic' => $data['bic'],
            'ks' => $data['ks'],
            'dn' => $data['dn'],
        ]);
    }
}
