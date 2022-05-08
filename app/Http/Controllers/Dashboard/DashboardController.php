<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Order;
use App\Models\Review;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mclass\Yakassa\Yakassa;

class DashboardController extends Controller
{

    protected $lesson;
    protected $order;
    protected $course;

    public function __construct(Video $lesson, Order $order, Course $course)
    {
        parent::__construct();
        $this->lesson = $lesson;
        $this->order = $order;
        $this->course = $course;
    }

    public function index()
    {

        $seminarsNotAvailable = Order::where('user_id', Auth::id())
            ->where('removed', '0')
            ->where(function ($query) {
                $query->where('status', '!=', 'succeeded')
                    ->orWhere('status', null);
            })
            ->orderBy('id', 'Desc')
            ->with(['seminar'])
            ->get();

        $seminarsAvailable = Order::where('user_id', Auth::id())
            ->where('status', 'succeeded')
            ->orderBy('id', 'Desc')
            ->with(['seminar'])
            ->get();

        //dd($seminarsAvailable);

        $seminarsStat = array();

        ///// Стастистика по курсам для преподов
        /// Мидлваром надо удет отправлять на другой метод преподов
        if (Auth::user()->role == 'teacher') {
            $seminarsStat = DB::table('orders')
                ->join('courses', 'orders.course_id', '=', 'courses.id')
                ->where('courses.teacher_id', '=', Auth::user()->teacher_id)
                ->where('orders.status', 'succeeded')
                ->orderBy('orders.id', 'Desc')
                ->select('orders.*', 'courses.title', 'courses.slug')
                ->get();
        }

        return view('dashboard.index', ['seminarsAvailable' => $seminarsAvailable,
            'seminarsNotAvailable' => $seminarsNotAvailable,
            'seminarsStat' => $seminarsStat,
        ]);
    }

    public function teacherComments()
    {
        return view('dashboard.comments');
    }

    public function teacherCourses()
    {

        $courses = Course::where('teacher_id', Auth::user()->teacher_id)
            ->with(['lessons', 'files', 'answers'])
            ->get();

        return view('dashboard.courses', ['courses' => $courses]);
    }

    public function teacherLesson($lessonId)
    {

        $courseId = $this->lesson->getCourseId($lessonId);

        $lesson = ($this->course->isTeacherCourse($courseId, Auth::user()->teacher_id)) ?
        Video::where('id', $lessonId)
            ->first() :
        false;
        $video = '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $lesson->video . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        return ($lesson->video != '') ? $video : 'Урок недоступен';
    }

    /**
     * Store a new lesson comment.
     *
     * @param  Request  $request
     * @return Response
     */
    public function postComment(Request $request)
    {
        $validatedData = $request->validate([
            'comment' => 'required',
        ]);

        // The comment is valid...
        $comment = new Comment([
            'lesson_id' => $request->get('lesson_id'),
            'parent_id' => 0,
            'user_id' => Auth::id(),
            'comment' => $request->get('comment'),
        ]);

        $comment->save();
        return redirect()->route('showLesson', ['id' => $request->get('lesson_id')])->with('success', 'Комментарий добавлен!');
    }

    /**
     * Store a course review.
     *
     * @param  Request  $request
     * @return Response
     */
    public function delete(Request $request)
    {
        $res = Order::where('id', $request->get('order_id'))
            ->where('user_id', Auth::id())
            ->update(['removed' => 1]);

        return ($res) ?
        redirect()->route('dashboard')->with('success', 'Семинар удален') :
        redirect()->route('dashboard')->with('error', 'Не удалось удалить семинар') ;
    }

    /**
     * Success page after ordering.
     *
     * @param  string $order_id, Yakassa $kassa
     * @return view result
     */
    public function success(string $order_id, Yakassa $kassa)
    {
        //// Получим yookassa_id заказа
        $order = Order::select('yookassa_id', 'course_id', 'status')->where('id', $order_id)->firstOrFail();

        if ($order->status != 'succeeded') {

            $yookassaResponseStatus = $kassa->getYakassaStatus($order->yookassa_id);

            //// Обновим статус заказа, полученный с Yookassa
            if ($yookassaResponseStatus) {
                Order::where('id', $order_id)->update(['status' => $yookassaResponseStatus]);
            }

        } else {
            $yookassaResponseStatus = 'succeeded';
        }

        if ($yookassaResponseStatus == 'succeeded') {
            $result = 'success';
            if ($order->status != 'succeeded') {
                $this->finalizeOrder($order_id);
            }

        } else {
            $result = 'error'; //// TODO error меня напрягает, надо что-то придумать
        }

        return view('dashboard.success', ['result' => $result, 'course_id' => $order->course_id]);

    }


}
