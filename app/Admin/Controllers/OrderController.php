<?php

namespace App\Admin\Controllers;

use App\Models\Order;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OrderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Заказы';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order());

        $grid->model()->orderBy('id', 'desc');

        $grid->disableCreateButton();
        $grid->disableFilter();
        $grid->disableRowSelector();
        $grid->disableColumnSelector();

        //$grid->disableActions();

        $grid->actions(function ($actions) {
            //$actions->disableDelete();
            //$actions->disableEdit();
            $actions->disableView();
        });


        $grid->column('id', __('Id'));
        $grid->column('users.name', __('Пользователь'));
        $grid->column('users.email', __('Почта'));
        $grid->column('course.title', __('Курс'));
        $grid->column('created_at', __('Дата'));
        $grid->column('price', __('Цена'));

        // column not in table
        $grid->column('PR')->display(function () {

            return ($this->coupon_code!='') ? $this->coupon_code.'|'.$this->coupon_percent.'%' : '' ;
        });

        $grid->column('status', __('Статус'));        
        //$grid->column('no_pay', __('NoPay'))->switch(['1' => true, '0' => false])->width(100);

        $grid->rows(function ($row) { 
            if ( $row->status != 'succeeded' ) {
                $row->style("color:gray");
            } else {
                $row->style("background-color:#88EE88;");
            }

        });

        $grid->column('no_pay', __('БО'))->display(function ($removed, $column) {  
             if ($this->no_pay == 1)
                    return '<i class="fa fa-plus" style="color:green"></i>';  
        });

        $grid->column('removed', __('Удален'))->display(function ($removed, $column) {  
             if ($this->removed == 1)
                    return '<i class="fa fa-times" style="color:red"></i>';  
        });

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
        $show = new Show(Order::findOrFail($id));

        $show->panel()
        ->tools(function ($tools) {
            $tools->disableEdit();
            //$tools->disableList();
            $tools->disableDelete();
        });

        $show->field('id', __('ID'));
        $show->field('course.title', __('Название'));
        $show->field('status', __('Статус'));
        $show->field('users.name', __('Имя'));
        $show->field('users.email', __('Email'));
        $show->field('yookassa_id', __('Yookassa ID'));
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
        $form = new Form(new Order());

        $form->disableReset();
        $form->disableViewCheck();
        $form->disableEditingCheck();
        $form->disableCreatingCheck();

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
        });

        $form->select('status', __('Статус'))->options(['succeeded' => 'Активен', 'pending' => 'В ожидании', 'canceled' => 'Отменен']);

        $states = [
            'on'  => ['value' => 1, 'text' => 'вкл', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => 'откл', 'color' => 'danger'],
        ];

        $form->switch('no_pay', __('Без оплаты'))->states($states);


        //$form->switch('status', __('Статус'))->options(['succeeded' => 'Включить', 'pending'=> 'Отключить']);
        //$form->switch('no_pay', __('NoPay'))->options(['1' => 'Есть', '0'=> 'Нет']);


        return $form;
    }
}
