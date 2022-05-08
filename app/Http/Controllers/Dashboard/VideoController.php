<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Quiz;
use App\Models\Video;
use App\Models\Seminar;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{

    public function video($id, Order $order)
    {

        $video = Video::where('id', $id)
            ->with('comments.user')
            ->first();

        $_order = $order->isSeminarAvailable($video->seminar_id, Auth::id());

        if (!$_order) {
            return view('dashboard.error', ['error' => 'Этот семинар вам недоступен.']);
        }

        /// Текущий уровень ученика в контексте заказанного семинара
        $currentLevel = $_order->level;

        if ($currentLevel < $video->level) {
            return view('dashboard.error', ['error' => 'Это видео вам недоступно.']);
        }

        $quizzes = Quiz::where('video_id', $id)
                    ->with('quiz_passed')
                    ->get();

        $seminar = Seminar::select('title', 'slug')
            ->where('id', $video->seminar_id)
            ->first();

        return view('dashboard.video', ['video' => $video, 'quizzes' => $quizzes, 'seminar' => $seminar]);
    }
}
