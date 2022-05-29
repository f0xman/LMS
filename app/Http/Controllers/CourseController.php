<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Seminar;
use App\Models\Order;
use Artesaos\SEOTools\Facades\SEOTools;

class CourseController extends Controller
{

    public function index()
    {
        $actualCourses = Course::where('active', '1')
                            ->where('end', '>', date('Y-m-d'))
                            ->orderBy('end', 'Asc')
                            //->with(['seminars'])
                            ->paginate(3);

        $category_meta = (request()->category) ? $category[request()->category] : '' ;
        $direction_meta = (request()->direction) ? 'онлайн курсы "'.$direction[request()->direction].'" ' : 'онлайн курсы по медицине ' ;
                    
        SEOTools::setTitle($direction_meta.$category_meta);
        SEOTools::setDescription('Онлайн курсы по медицине для профессионалов и людей без медицинского образования');

        SEOTools::opengraph()->setTitle('MClass - онлайн курсы по медицине');
        SEOTools::opengraph()->setDescription('Онлайн курсы по медицине для профессионалов и людей без медицинского образования');
        SEOTools::opengraph()->addImage(['url' => config('app.url').'/assets/img/top.jpg']);

        return view('frontend.courses', ['courses' => $actualCourses, 'archived' => false]);

    }

    public function archived()
    {
        $archivedCourses = Course::where('active', '1')
                            ->where('end', '<', date('Y-m-d'))
                            ->orderBy('end', 'Desc')
                            ->with(['seminars'])
                            ->paginate(3);

        $category_meta = (request()->category) ? $category[request()->category] : '' ;
        $direction_meta = (request()->direction) ? 'онлайн курсы "'.$direction[request()->direction].'" ' : 'онлайн курсы по медицине ' ;
                    
        SEOTools::setTitle($direction_meta.$category_meta);
        SEOTools::setDescription('Онлайн курсы по медицине. Архив курсов');

        SEOTools::opengraph()->setTitle('MClass - онлайн курсы по медицине');
        SEOTools::opengraph()->setDescription('Онлайн курсы по медицине для профессионалов и людей без медицинского образования');
        SEOTools::opengraph()->addImage(['url' => config('app.url').'/assets/img/top.jpg']);

        return view('frontend.courses', ['courses' => $archivedCourses, 'archived' => true]);

    }

    public function show($slug)
    {
        $course = Course::where('slug', $slug)
                            ->where('active', '1')
                            ->firstOrFail();

        $seminars = Seminar::where('course_id', $course->id)
                    ->with(['teacher'])
                    ->get();

        $purchasedCoursesIds = array();

        if(Auth::user()) {
            $purchasedCoursesIds = Order::select('seminar_id')
                                        ->where('user_id', Auth::id())
                                        ->where('removed', 0)   
                                        ->pluck('seminar_id')
                                        ->toArray();
        }

        $isFinished = ($course->end < date('Y-m-d')) ? true : false ;

        SEOTools::setTitle($course->title);
        SEOTools::setDescription($course->anonce);

        SEOTools::opengraph()->setTitle($course->title);
        SEOTools::opengraph()->setDescription($course->anonce);
        SEOTools::opengraph()->addImage(['url' => config('app.url').'/uploads/'.$course->image]);

        return view('frontend.course-detail', ['course' => $course, 
                                                'seminars' => $seminars, 
                                                'purchased' => $purchasedCoursesIds,
                                                'isFinished' => $isFinished
                                                ]);

    }

}
