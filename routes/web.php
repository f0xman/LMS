<?php

use App\Http\Middleware\IsUserAuth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', [App\Http\Controllers\IndexController::class, 'index'])->name('main');

Route::get('/courses', [App\Http\Controllers\CourseController::class, 'index'])->name('courses');
Route::get('/course/{slug}', [App\Http\Controllers\CourseController::class, 'show'])->name('courseShow');
Route::get('/courses/archived', [App\Http\Controllers\CourseController::class, 'archived'])->name('archived');
//Route::get('/course/teacher/{id}', [App\Http\Controllers\CourseController::class, 'teacher'])->name('courseTeacher');
//Route::post('/course/checkcoupon', [App\Http\Controllers\CourseController::class, 'checkCoupon'])->name('checkCoupon');

Route::get('/teachers', [App\Http\Controllers\TeacherController::class, 'index'])->name('teachers');
Route::get('/teacher/{id}', [App\Http\Controllers\TeacherController::class, 'show'])->name('teacherShow');

Route::any('/order', [App\Http\Controllers\OrderController::class, 'index'])->name('order')->middleware(IsUserAuth::class);
Route::any('/order/notifier', [App\Http\Controllers\OrderController::class, 'notifier'])->name('notifier');

Route::get('/about', function () {
    return view('frontend.about');
});

Route::get('/contacts', function () {
    return view('frontend.contacts');
});

Route::get('/terms', function () {
    return view('frontend.terms');
});

Route::get('/personal-data', function () {
    return view('frontend.personal-data');
});

Auth::routes();

Route::group(['middleware' => 'auth', 'prefix' => 'dashboard'], function () {

    Route::get('/', [App\Http\Controllers\Dashboard\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/', [App\Http\Controllers\Dashboard\DashboardController::class, 'delete'])->name('delete');

    Route::get('/seminar/{id}', [App\Http\Controllers\Dashboard\SeminarController::class, 'seminar'])->name('showSeminar');
    Route::post('/seminar/{id}', [App\Http\Controllers\Dashboard\SeminarController::class, 'postReview'])->name('postReview');

    Route::get('/video/{id}', [App\Http\Controllers\Dashboard\VideoController::class, 'video'])->name('showVideo');
    Route::post('/video/{id}', [App\Http\Controllers\Dashboard\VideoController::class, 'postComment'])->name('postComment');

    Route::get('/quiz/{id}', [App\Http\Controllers\Dashboard\QuizController::class, 'quiz'])->name('showQuiz');
    Route::post('/quiz', [App\Http\Controllers\Dashboard\QuizController::class, 'postAnswers'])->name('postAnswers');

    //Route::get('/coupons', [App\Http\Controllers\Dashboard\DashboardController::class, 'coupons'])->name('coupons');

    Route::get('/success/{id}', [App\Http\Controllers\Dashboard\DashboardController::class, 'success'])->name('success');

    Route::get('/support', [App\Http\Controllers\Dashboard\SupportController::class, 'index'])->name('support');
    Route::get('/support/{id}', [App\Http\Controllers\Dashboard\SupportController::class, 'showSupport'])->name('showSupport');
    Route::post('/support', [App\Http\Controllers\Dashboard\SupportController::class, 'postSupport'])->name('postSupport');
    Route::post('/support/{id}', [App\Http\Controllers\Dashboard\SupportController::class, 'postQuestion'])->name('postQuestion');

    Route::get('/profile', [App\Http\Controllers\Dashboard\ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/saveProfile', [App\Http\Controllers\Dashboard\ProfileController::class, 'saveProfile'])->name('saveProfile');
    Route::post('/profile/savePassword', [App\Http\Controllers\Dashboard\ProfileController::class, 'savePassword'])->name('savePassword');

    Route::get('/comments', [App\Http\Controllers\Dashboard\DashboardController::class, 'teacherComments'])->name('teacherComments');
    Route::get('/courses', [App\Http\Controllers\Dashboard\DashboardController::class, 'teacherCourses'])->name('teacherCourses');
    Route::get('/teacherlesson/{id}', [App\Http\Controllers\Dashboard\DashboardController::class, 'teacherLesson'])->name('teacherLesson');

    Route::get('/certificates', [App\Http\Controllers\Dashboard\CertificateController::class, 'index'])->name('certificates');
    Route::post('/certificates', [App\Http\Controllers\Dashboard\CertificateController::class, 'generateCertificate'])->name('generateCertificate');

});
