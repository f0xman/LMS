<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;

use Artesaos\SEOTools\Facades\SEOTools;

class TeacherController extends Controller
{

    public function index()
    {
        return view('frontend.teachers', ['teachers' => Teacher::orderBy('id', 'desc')->paginate(12)]);
    }

    public function show($id)
    {
        $teacher = Teacher::where('id', $id)->firstOrFail();

        SEOTools::setTitle(' преподаватель '.$teacher->name);
        SEOTools::setDescription($teacher->description);

        SEOTools::opengraph()->setTitle('Преподаватель '.$teacher->name);
        SEOTools::opengraph()->setDescription($teacher->description);
        SEOTools::opengraph()->addImage(['url' => config('app.url').'/uploads/'.$teacher->image]);

        return view('frontend.teacher-detail', ['teacher' => $teacher]);

    }

}
