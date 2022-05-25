<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Models\Course;

use Artesaos\SEOTools\Facades\SEOTools;

class IndexController extends Controller
{

    public function index(Course $course)
    {
        SEOTools::setTitle('школа медицины и здоровья онлайн');
        SEOTools::setDescription('Первая обучающая платформа в области медицины и здоровья для врачей, студентов медицинских вузов и людей без медицинского образования');

        SEOTools::opengraph()->setTitle('MClass - школа медицины и здоровья онлайн');
        SEOTools::opengraph()->setDescription('Первая обучающая платформа в области медицины и здоровья для врачей, студентов медицинских вузов и людей без медицинского образования');
        SEOTools::opengraph()->addImage(['url' => config('app.url').'/assets/img/top.jpg']);

        return view('frontend.index', ['courses' => $course->getTopCourses()]);
    }


}
