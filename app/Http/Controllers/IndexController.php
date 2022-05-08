<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Teacher;

use Artesaos\SEOTools\Facades\SEOTools;

class IndexController extends Controller
{

    public function index()
    {
        $courses = Course::where('top', '1')
                            ->where('active', '1')
                            ->with(['seminars'])
                            ->get();


        //$morph = array('', 'курса', 'курсов');

        //$countPro = ending(Seminar::where('course_id', $courses->id)->count(), $morph);
        //$countCommon = ending(Course::where('category_id', 2)->count(), $morph);

        $teachers = Teacher::all();

        SEOTools::setTitle('школа медицины и здоровья онлайн');
        SEOTools::setDescription('Первая обучающая платформа в области медицины и здоровья для врачей, студентов медицинских вузов и людей без медицинского образования');

        SEOTools::opengraph()->setTitle('MClass - школа медицины и здоровья онлайн');
        SEOTools::opengraph()->setDescription('Первая обучающая платформа в области медицины и здоровья для врачей, студентов медицинских вузов и людей без медицинского образования');
        SEOTools::opengraph()->addImage(['url' => config('app.url').'/assets/img/top.jpg']);

        return view('frontend.index', ['courses' => $courses, 'teachers' => $teachers]);

    }


}
