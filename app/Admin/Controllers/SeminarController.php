<?php

namespace App\Admin\Controllers;

use App\Models\Seminar;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\Access;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SeminarController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Семинары';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Seminar());

        $grid->model()->orderBy('id', 'desc');

        $grid->disableFilter();
        $grid->disableRowSelector();
        $grid->disableColumnSelector();
        $grid->disableExport();

        $grid->filter(function($filter){
            // Remove the default id filter
            $filter->disableIdFilter();
        });

        $grid->actions(function ($actions) {
            $actions->disableView();
        });

        $grid->column('id', __('Id'))->width(50);

        $grid->column('title', __('Название семинара'))->display(function ($title) {
            return "<span class='h4 text-bold'> {$title} </span>";
        });

        $grid->column('date', __('Дата'))->width(100);

        $grid->column('course_id','Курс')->display(function () {
            return $this->course->title;
        })->color('#666666');

        $grid->column('price', __('Цена'))->width(100);

        $grid->column('teacher_id','Преподаватель')->display(function () {
            $cert = ($this->teacher->certificate!='') ? ' <i class="fa fa-certificate" style="color:green"></i>' : '' ;
            return $this->teacher->name.$cert;
        })->color('#666666');

        $grid->column('image', __('Фото'))->image('', 100, 100);

        $grid->column('top', __('Top'))->switch(['0' => true, '1' => false])->width(100);
        $grid->column('off', __('Выкл'))->switch(['0' => true, '1' => false])->width(100);
        $states = [
            'on'  => ['value' => 1, 'text' => 'Есть'],
            'off' => ['value' => 0, 'text' => 'Нет'],
        ];

        return $grid;
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Seminar);

        $form->disableReset();
        $form->disableViewCheck();
        $form->disableEditingCheck();
        $form->disableCreatingCheck();

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
        });

        $form->tab('Основная информация', function ($form) {

        $form->text('title', __('Название семинара'))->rules('required');
        $form->text('slug', __('URL'))->rules('required');

        $form->date('date', __('Дата'))->rules('required');

        $form->select('course_id', __('Курс'))->options(Course::all()->pluck('title','id'))->rules('required');
        $form->select('teacher_id', __('Преподаватель'))->options(Teacher::all()->pluck('name','id'))->rules('required');

        $form->text('price', __('Цена курса'))->rules('required');
        $form->text('old_price', __('Старая цена'));
  
        $form->switch('top', __('Top'))->options(['1' => 'Да', '0'=> 'Нет'])->default('0');
        $form->switch('off', __('Выкл'))->options(['1' => 'Выкл', '0'=> 'Вкл'])->default('0');

        $states = [
            'on'  => ['value' => 1, 'text' => 'Есть'],
            'off' => ['value' => 0, 'text' => 'Нет'],
        ];

        $form->ckeditor('about', __('Описание курса'))->rules('required');
        $form->textarea('anonce', __('Анонс'))->rules('required');


        ////// ФОТО
        })->tab('Фото', function ($form) {

        $form->image('image', __('Фото (800x533)'))->rules('required')->thumbnail('small', $width = 350, $height = 233)->removable()->uniqueName();

        $form->text('video', __('Видео промо'));
        

        })->tab('Видео курса', function ($form) {

        $form->hasMany('videos', 'Добавить видео', function (Form\NestedForm $form) {
            $form->text('title', __('Название видео'))->rules('required');
            $form->text('video', __('ID видео'))->rules('required');
            $form->select('level', __('Уровень доступа'))->options(Access::all()->pluck('title','id'))->rules('required');
        });

        })->tab('Файлы курса', function ($form) {

        $form->hasMany('files', 'Добавить файл', function (Form\NestedForm $form) {
            $form->text('title', __('Название файла'));//->rules('required');
            $form->file('file', __('Файл'))->rules('mimes:doc,docx,xlsx,pdf')->removable()->downloadable()->uniqueName();
            $form->select('level', __('Уровень доступа'))->options(Access::all()->pluck('title','id'));
            });

        });


        return $form;
    }
}
