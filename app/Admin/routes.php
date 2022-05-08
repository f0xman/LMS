<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    $router->get('/api/video', 'QuizController@video')->name('video');

    $router->resource('courses', CourseController::class);
    $router->resource('quizes', QuizController::class);
    $router->resource('seminars', SeminarController::class);
    $router->resource('accesses', AccessController::class);
    $router->resource('teachers', TeacherController::class);
  //  $router->resource('directions', DirectionController::class);
    $router->resource('orders', OrderController::class);
    $router->resource('support', SupportController::class);
    $router->resource('comments', CommentController::class);
    $router->resource('users', UserController::class);
   // $router->resource('coupons', CouponController::class);

   // $router->resource('actions', ActionController::class);
   // $router->resource('promocodes', PromocodeController::class);
  //  $router->resource('banners', BannerController::class);


});
