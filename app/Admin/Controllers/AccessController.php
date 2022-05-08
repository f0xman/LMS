<?php

namespace App\Admin\Controllers;

use App\Models\Access;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AccessController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Уровни доступа';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Access());

        $grid->disableFilter();
        $grid->disableRowSelector();
        $grid->disableColumnSelector();
        $grid->disablePagination();
        $grid->disableExport();

        $grid->actions(function ($actions) {
            //$actions->disableDelete();
            //$actions->disableEdit();
            $actions->disableView();
        });

        $grid->column('id', __('Id'));
        $grid->column('title', __('Название уровня'));

        return $grid;
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Access());

        $form->disableReset();
        $form->disableViewCheck();
        $form->disableEditingCheck();
        $form->disableCreatingCheck();

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
        });

        $form->text('title', __('Название уровня'))->rules('required');

        return $form;
    }
}
