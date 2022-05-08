<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
Use Encore\Admin\Admin;

use App\Models\Promocode;
use App\Models\User;
use App\Models\Course;

use Illuminate\Support\Str;

class PromocodeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Промокоды';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Promocode());

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
        $grid->column('code', __('Код'));
        $grid->column('percent', __('Процент'));

        $grid->column('start', __('Начало'));
        $grid->column('end', __('Конец'));

        $grid->column('off', __('Выкл'))->switch(['0' => true, '1' => false])->width(100);

        $grid->rows(function ($row) {
           if ( $row->column('start') < date("Y-m-d H:i:s") && $row->column('end') > date("Y-m-d H:i:s", strtotime("- 1 day")) ) {
                ////// АКТИВНЫЙ КОД
                }
           else {
                $row->style("color:gray");
           }  
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
        $form = new Form(new Promocode());

        $form->disableReset();
        $form->disableViewCheck();
        $form->disableEditingCheck();
        $form->disableCreatingCheck();

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
        });

        $form->text('code', __('Код (мин. 5 знаков)'))->rules('required|min:5');
        $form->number('percent', __('Процент'))->min(1)->max(99)->rules('required');

        $form->dateRange('start', 'end', 'Дата проведения')->rules('required');

        $form->switch('off', __('Выкл'))->options(['1' => 'Выкл', '0'=> 'Вкл'])->default('0');

        
        $form->multipleSelect('courses', __('Курсы'))
            ->options(Course::all()->sortByDesc('title')
            ->pluck('title', 'id'))
            ->attribute(['id' => 'e1'])
            ->rules('required');

        $form->html('<input type="checkbox" id="checkbox"> Выбрать все курсы');

        Admin::script('$("#e1").select2();
        $("#checkbox").click(function(){
            if($("#checkbox").is(\':checked\') ){
                $("#e1 > option").prop("selected","selected");
                $("#e1").trigger("change");
            }else{
                $("#e1 > option").removeAttr("selected");
                 $("#e1").trigger("change");
             }
        });');

        return $form;
    }
}
