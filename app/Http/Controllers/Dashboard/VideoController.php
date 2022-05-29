<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Video;
use App\Models\Seminar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{

    public function video($id)
    {

        $video = Video::where('id', $id)
                    //->with('comments.user')
                    ->first();

        if(!$this->isContentAvailable($video->seminar_id)) {
            return view('dashboard.error', ['error' => 'Этот семинар вам недоступен.']); 
        }

        if ($this->currentUserLevel < $video->level) {
            return view('dashboard.error', ['error' => 'Это видео вам недоступно.']);
        }

        $quizzes = Quiz::where('video_id', $id)
                    ->with('quiz_passed')
                    ->get();

        return view('dashboard.video', ['video' => $video, 'quizzes' => $quizzes]);
    }

     /**
     * Этот функционл пока не работает
     *
     * @return Response
     */
    public function postComment(Request $request)
    {
        $validatedData = $request->validate([
            'comment' => 'required',
        ]);

        $comment = new Comment([
            'video_id' => $request->get('video_id'),
            'parent_id' => 0,
            'user_id' => Auth::id(),
            'comment' => $request->get('comment'),
        ]);
        $comment->save();

        return redirect()->route('showVideo', ['id' => $request->get('video_id')])->with('success', 'Комментарий добавлен!');
    }

}
