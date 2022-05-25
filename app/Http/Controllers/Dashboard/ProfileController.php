<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

use Hash;

class ProfileController extends Controller
{

    public function index()
    {

        return view('dashboard.profile', ['profile' => Auth::user()
                                                            ->select('name', 'email')
                                                            ->where('id', Auth::id())
                                                            ->first() ]);
    }

     /**
     * Store a profile changes.
     *
     * @param  Request  $request
     * @return Response
     */
    public function saveProfile(Request  $request)
    {

        $emailunique = (Auth::user()->email==$request->get('email')) ? '' : '|unique:users' ;

        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required'.$emailunique

        ]);

        User::where('id', Auth::id())->update($request->only(['name','email']));

        return redirect()->route('profile')->with('success', 'Данные успешно сохранены!');

    }

     /**
     * Store a new password.
     *
     * @param  Request  $request
     * @return Response
     */
    public function savePassword(Request  $request)
    {
        $validatedData = $request->validate([
            'password' => 'required',
            'new_password' => 'required|min:8'
        ]);

        if(Hash::check($request->get('password'), Auth::User()->password)) {

            //Change Password
            $user = Auth::user();
            $user->password = bcrypt($request->get('new_password'));
            $user->save();
            
            return redirect()->route('profile')->with('success', 'Пароль успешно изменен!');

        } else {

            return redirect()->back()->with("error","Текущий пароль не верен!");

        }

    }
}
