<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Review;
use App\Models\Seminar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\SeminarService;


class SeminarController extends Controller
{

    public function seminar($id, SeminarService $service)
    {

        if(!$this->isContentAvailable($id)) {
            return view('dashboard.error', ['error' => 'Этот семинар вам недоступен.']); 
        }

        $seminar = Seminar::select('title', 'id', 'about', 'course_id')
            ->where('id', $id)
            ->with(['videos', 'files', 'course'])
            ->first();

        $content = $service->handler($seminar, $this->currentUserLevel);

        $isReviewExist = Review::where([['course_id', $id], ['user_id', Auth::id()]])->exists();

        return view('dashboard.seminar', ['seminar' => $seminar,
    
            'unAvailableVideos' => $content['unAvailableVideos'],
            'availableVideos' => $content['availableVideos'],
            'availableFiles' => $content['availableFiles'],
            'unAvailableFiles' => $content['unAvailableFiles'],
            'isReviewExist' => $isReviewExist,
        ]);
    }

    /**
     * Сохранение отзыва о семинаре
     *
     * @return Response
     */
    public function postReview(Request $request)
    {
        $validatedData = $request->validate([
            'rating' => 'required',
            'review' => 'required',
        ]);

        $comment = new Review([
            'rating' => $request->get('rating'),
            'course_id' => $request->get('course_id'),
            'user_id' => Auth::id(),
            'review' => $request->get('review'),
        ]);

        $comment->save();
        return redirect()->route('showCourse', ['id' => $request->get('course_id')])->with('success', 'Отзыв добавлен. Спасибо!');
    }

}

