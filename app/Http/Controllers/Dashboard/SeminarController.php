<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Order;
use App\Models\Review;
use App\Models\Seminar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeminarController extends Controller
{

    public function seminar($id, Order $order)
    {

        $_order = $order->isSeminarAvailable($id, Auth::id());

        if (!$_order) {
            return view('dashboard.error', ['error' => 'Этот семинар вам недоступен.']);
        }

        /// Текущий уровень ученика в контексте заказанного семинара
        $currentLevel = $_order->level;

        $seminar = Seminar::select('title', 'id', 'about', 'course_id')
            ->where('id', $id)
            ->with(['videos', 'files', 'course'])
            ->first();

        $course = Course::select('title', 'slug')
            ->where('id', $seminar->course_id)
            ->first();

        $availableVideos = $unAvailableVideos = $availableFiles = $unAvailableFiles = array();

        if (!empty($seminar->videos)) {
            foreach ($seminar->videos as $video) {
                if ($currentLevel >= $video->level) {
                    $availableVideos[] = $video;
                } else {
                    $unAvailableVideos[] = $video;
                }
            }
        }

        if (!empty($seminar->files)) {
            foreach ($seminar->files as $file) {
                if ($currentLevel >= $file->level) {
                    $availableFiles[] = $file;
                } else {
                    $unAvailableFiles[] = $file;
                }
            }
        }

        $isReviewExist = Review::where([['course_id', $id], ['user_id', Auth::id()]])->exists();

        return view('dashboard.seminar', ['seminar' => $seminar,
            'course' => $course,
            'unAvailableVideos' => $unAvailableVideos,
            'availableVideos' => $availableVideos,
            'availableFiles' => $availableFiles,
            'unAvailableFiles' => $unAvailableFiles,
            'isReviewExist' => $isReviewExist,
        ]);
    }

    /**
     * Сохранение отзыва о семинаре
     *
     * @param  Request  $request
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

