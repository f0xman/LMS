<?php

namespace App\Admin\Controllers;

use App\Models\Direction;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DirectionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Direction';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Direction());

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
        $grid->column('direction', __('Направление'));

        return $grid;
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Direction());
        $form->disableReset();
        $form->disableViewCheck();
        $form->disableEditingCheck();
        $form->disableCreatingCheck();

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
        });

        $form->text('direction', __('Направление'));

        return $form;
    }
}
