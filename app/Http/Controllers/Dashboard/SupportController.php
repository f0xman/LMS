<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SupportMail;

use App\Models\Support;
use App\Models\SupportMessage;


class SupportController extends Controller
{

    ///////// Этот функционал ПОКА НЕ РАБОТЕТ
    ///////// НУЖЕН РЕФАКТОРИНГ
    protected $support;

    public function __construct(Support $support)
    {
        parent::__construct();
        $this->support = $support;
    }


    public function index()
    {

        $support = Support::where('user_id', Auth::id())
                            ->orderBy('id', 'Desc')
                            ->with(['supportmessages' => function ($query) {
                                $query->where('admin',1)->latest('created_at')->first();
                            }])
                            ->get();
             
        return view('dashboard.support', ['support' => $support]);
    }


    public function showSupport($id) // проверка на принадлежность тикета юзеру
    {

        $parent = $this->support->getSupportById($id, Auth::id());

        if ($parent) {

                $support = SupportMessage::where('support_id', $id)
                            ->orderBy('id', 'Desc')
                            ->get();
                $support->title = $parent->title;
                $support->root_id = $parent->id;

                //// Пометим все непрочитанные сообщения ветки в 0
                if ($support->contains('unread', '1')) {
                    SupportMessage::where('support_id', $id)->update(['unread' => 0]);
                }

        } else {
                $support = 'Это тикет вам недоступен';
        }
        

        return view('dashboard.support_messages', ['support' => $support]);
    }


     /**
     * Store a new support request.
     *
     * @param  StorePostSupport  $request
     * @return Response
     */
     public function postSupport(StorePostSupport $request)
    {
        $support = new Support([
            'title' => $request->get('title'),
            'user_id' => Auth::id()
        ]);

        if ( $support->save() ) {

            $support_message = new SupportMessage([
                'support_id' => $support->id,
                'admin' => 0,
                'unread' => 0,
                'user_id' => Auth::id(),
                'message' => $request->get('message')
            ]);

            $support_message->save();
        }


        ///// Данные в шаблон письма
        $email_data = array(
            'title' => $request->get('title'),
            'message' => $request->get('message'),
            'name' => Auth::user()->name,
            'email' => Auth::user()->email
        );

        Mail::to("info@mclass.pro")->send(new SupportMail($email_data));

        return redirect()->route('postSupport')->with('success', 'Сообщение отправлено!');
    }


     /**
     * Store a student support message.
     *
     * @param  Request  $request
     * @return Response
     */
     public function postQuestion(Request $request)
    {
        $validatedData = $request->validate([
            'message' => 'required',
        ]);

        $support_message = new SupportMessage([
            'support_id' => $request->get('support_id'),
            'message' => $request->get('message'),
            'user_id' => Auth::id()
        ]);

        $support_message->save();

        return redirect()->route('postQuestion', ['id' => $request->get('support_id')])->with('success', 'Сообщение отправлено!');
    }
    
}
