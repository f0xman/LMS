<?php

namespace App\Admin\Controllers;

use App\Models\Teacher;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TeacherController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Преподаватели';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Teacher());
        $grid->model()->orderBy('id', 'desc');

        $grid->disableFilter();
        $grid->disableRowSelector();
        $grid->disableColumnSelector();
        $grid->disableExport();

        $grid->actions(function ($actions) {
            //$actions->disableDelete();
            //$actions->disableEdit();
            $actions->disableView();
        });

        $grid->column('id', __('Id'));
        $grid->column('name', __('Имя'));
        $grid->column('description', __('Короткий текст'));
        $grid->column('image', __('Фото'))->image('', 100, 100);
        $grid->column(('Сертификат'))->display(function ($removed, $column) {  
             if ($this->certificate != '')
                    return '<i class="fa fa-plus" style="color:green"></i>';  
        });
        

        return $grid;
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Teacher());

        $form->disableReset();
        $form->disableViewCheck();
        $form->disableEditingCheck();
        $form->disableCreatingCheck();

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
        });

        $form->text('name', __('Имя'))->rules('required');
        $form->image('image', __('Фото'))->rules('required')->thumbnail('small', $width = 150, $height = 150)->removable()->uniqueName();
        $form->image('certificate', __('Сертификат'))->removable()->uniqueName();
        $form->text('description', __('Короткий текст'))->rules('required');
        $form->ckeditor('about', __('О преподавателе'))->rules('required');        
        $form->text('fb', __('FB'));
        $form->text('vk', __('VK'));
        $form->text('insta', __('Insta'));

        return $form;
    }
}
