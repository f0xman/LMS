<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Models\Teacher;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use Illuminate\Support\Facades\Hash;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Пользователи';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        $grid->model()->orderBy('id', 'desc');

        $grid->disableFilter();
        $grid->disableRowSelector();
        $grid->disableColumnSelector();
        $grid->disableExport();

        $grid->actions(function ($actions) {
            //$actions->disableDelete();
            //$actions->disableEdit();
            //$actions->disableView();
        });

        $grid->column('id', __('Id'));
        $grid->column('name', __('Имя'));

        //$grid->column('name')->value(function ($name) {
        //    return "<a href='/admin/users/{$this->page_id}/edit'>$name</a>";
        //});

        $grid->column('email', __('Email'));
        $grid->column('role', __('Роль'));

        $grid->rows(function ($row) { 
            if ( $row->role == 'teacher' ) 
                    $row->style("background-color:#FCE477; font-weight: bold");
        });

        $grid->column('player_off', __('Плеер'))->switch(['0' => 'Вкл', '1' => 'Выкл'])->width(100);

        $grid->column('created_at', __('Регистрация'));

        $grid->column('ip', __('IP'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('name', __('Имя'));
        $show->field('email', __('Email'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User());

        $form->disableReset();
        $form->disableViewCheck();
        $form->disableEditingCheck();
        $form->disableCreatingCheck();

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
        });


        $form->select('teacher_id', __('Преподаватель'))->options(Teacher::all()->pluck('name','id'))->rules('required');
        $form->text('name', __('Имя'))->rules('required');        

    if ($form->isCreating()){
        $form->text('email', __('Email'))->rules('required|unique:users');
        $form->password('password', __('Пароль'))->rules('required|min:8');   
    }

    if ($form->isEditing()){
        $form->text('email', __('Email'))->rules('required');
    }

        $form->select('role', __('Тип аккаунта'))->options(['student' => 'Студент', 'teacher' => 'Преподаватель']);
        $form->switch('player_off', __('Плеер отключен'))->options(['1' => 'Выкл', '0'=> 'Вкл'])->default('0');

        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = Hash::make($form->password);
            }
        });

        return $form;
    }
}
