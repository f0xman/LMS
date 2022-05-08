<?php

// Home
Breadcrumbs::for('main', function ($trail) {
    $trail->push('Главная', route('main'));
});

// Home > Courses
Breadcrumbs::for('courses', function ($trail) {
    $trail->parent('main');
    $trail->push('Курсы', route('courses'));
});

// Home > Courses > [Course]
Breadcrumbs::for('courseShow', function ($trail, $course) {
    $trail->parent('courses');
    $trail->push($course->title, route('courseShow', $course->slug));
});

// Home > Teachers
Breadcrumbs::for('teachers', function ($trail) {
    $trail->parent('main');
    $trail->push('Преподаватели', route('teachers'));
});

// Home > Teachers > [Teacher]
Breadcrumbs::for('teacherShow', function ($trail, $teacher) {
    $trail->parent('teachers');
    $trail->push($teacher->name, route('teacherShow', $teacher->id));
});
