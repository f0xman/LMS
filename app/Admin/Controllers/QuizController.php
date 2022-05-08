<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use DB;

use App\Models\Seminar;
use App\Models\Video;
use App\Models\Quiz;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class QuizController extends AdminController
{

    protected $title = 'Вопросы';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Quiz());
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

        $grid->column('id', __('Id'));
        $grid->column('title', __('Название квиза'));
        $grid->column('video.title', __('Видео'));
        $grid->column('video.level', __('Видео уровень'));

        return $grid;
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Quiz());

        $form->disableReset();
        $form->disableViewCheck();
        $form->disableEditingCheck();
        $form->disableCreatingCheck();

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
        });

        $form->tab('Основная информация', function ($form) {

            $form->text('title', __('Название квиза'))->rules('required');

            $form->select('seminar_id', 'Семинар')->options(Seminar::all()->pluck('title', 'id'))->load('video_id', '/admin/api/video')->rules('required');

            $form->select('video_id', 'Видео')->rules('required')->options(function ($id) {
                return Video::where('id', $id)->pluck('title', 'id');
            });

        ////// ВОПРОСЫ
        })->tab('Вопросы', function ($form) {

            $form->hasMany('questions', 'Добавить вопрос', function (Form\NestedForm $form) {
                $form->textarea('question', __('Вопрос'))->rules('required');
                $form->text('v1', __('Вариант 1 (правильный)'))->rules('required');
                $form->text('v2', __('Вариант 2'))->rules('required');
                $form->text('v3', __('Вариант 3'))->rules('required');
                $form->text('v4', __('Вариант 4'))->rules('required');    
            });
        
        });

        return $form;
    }

    
    public function video(Request $request)
    {
        $seminarId = $request->get('q');      
        return Video::where('seminar_id', $seminarId)->get(['id', DB::raw('title as text')]);
    }  

}

