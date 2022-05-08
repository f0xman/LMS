<?php

namespace App\Admin\Controllers;

use App\Models\Course;
use App\Models\Teacher;
use App\Models\Category;
use App\Models\Direction;
use App\Models\Lesson;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CourseController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Курсы';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Course());

        $grid->model()->orderBy('id', 'desc');

        $grid->disableFilter();
        $grid->disableRowSelector();
        $grid->disableColumnSelector();
        $grid->disableExport();

        $grid->filter(function($filter){
            $filter->disableIdFilter();
        });

        $grid->actions(function ($actions) {
            $actions->disableView();
        });

        $grid->column('id', __('Id'))->width(50);

        $grid->column('title', __('Название курса'))->display(function ($title) {
            return "<span class='h4 text-bold'> {$title} </span>";
        });

        $grid->column('data', __('Дата'))->display(function () {
            return 'с '.$this->start . '<br />по ' . $this->end;
        })->replace(['00:00:00' => '-']);

        //$grid->column('start', __('Начало'));
        //$grid->column('end', __('Конец'));

        $grid->column('image', __('Фото'))->image('', 100, 100);

        $grid->column('top', __('Top'))->switch(['0' => true, '1' => false])->width(100);
        
        $grid->column('active', __('Активен'))->switch(['0' => true, '1' => false])->width(100);

        return $grid;
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Course());

        $form->disableReset();
        $form->disableViewCheck();
        $form->disableEditingCheck();
        $form->disableCreatingCheck();

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
        });

        $form->tab('Основная информация', function ($form) {

        $form->text('title', __('Название курса'))->rules('required');
        $form->text('slug', __('URL'))->rules('required');

        //$form->datetime('start', __('Начало'))->rules('required');

        //$form->datetime('end', __('Конец'))->rules('required');

        $form->dateRange('start', 'end', 'Дата проведения')->rules('required');



        $form->switch('top', __('Top'))->options(['1' => 'Да', '0'=> 'Нет'])->default('0');
        $form->switch('active', __('Активен'))->options(['1' => 'Выкл', '0'=> 'Вкл'])->default('1');

        $states = [
            'on'  => ['value' => 1, 'text' => 'Есть'],
            'off' => ['value' => 0, 'text' => 'Нет'],
        ];

        $form->image('image', __('Фото (800x533)'))->rules('required')->thumbnail('small', $width = 350, $height = 233)->removable()->uniqueName();

        $form->ckeditor('about', __('Описание курса'))->rules('required');
        $form->textarea('anonce', __('Анонс'))->rules('required');

        });

        return $form;
    }
}
