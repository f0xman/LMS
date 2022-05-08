<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use App\Models\Coupon;
use App\Models\User;

use Illuminate\Support\Str;

class CouponController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Купоны';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Coupon());

        $grid->model()->orderBy('id', 'desc');

        $grid->disableFilter();
        $grid->disableRowSelector();
        $grid->disableColumnSelector();
        $grid->disableExport();

        $grid->actions(function ($actions) {
            $actions->disableDelete();
            //$actions->disableEdit();
            $actions->disableView();
        });

        $grid->column('id', __('Id'));
        $grid->column('percent', __('Процент'));
        //$grid->column('user.email', __('Студент'));
        $grid->column('code', __('Код'));

        $grid->column('user_id','Студент')->display(function () {
            return $this->user->name.' ('.$this->user->email.')';
        });

        $grid->rows(function ($row) { 
            if ( $row->used == 1 ) 
                    $row->style("background-color:#F2F2F2; color:gray");
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
        $form = new Form(new Coupon());

        $form->disableReset();
        $form->disableViewCheck();
        $form->disableEditingCheck();
        $form->disableCreatingCheck();

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
        });

        $form->text('percent', __('Процент'))->rules('required');
        $form->select('user_id', __('Студент'))->options(User::all()->pluck('email','id'))->rules('required');

        if ($form->isCreating()){
            $form->hidden('code');
            $form->saving(function (Form $form) {
                   $form->code = Str::random(5);
            });
        }

        return $form;
    }
}
