<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use App\Models\Banner;
use App\Models\User;

use Illuminate\Support\Str;

class BannerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Баннеры';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Banner());

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
        $grid->column('title', __('Название'));
        $grid->column('url', __('URL'));
        $grid->column('image', __('Фото'))->image('', 100, 100);
        $grid->column('off', __('Выкл'))->switch(['0' => true, '1' => false])->width(100);

        return $grid;
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Banner());

        $form->disableReset();
        $form->disableViewCheck();
        $form->disableEditingCheck();
        $form->disableCreatingCheck();

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
        });

        $form->text('title', __('Название'))->rules('required');
        $form->text('url', __('URL'))->rules('required');
        $form->image('image', __('Баннер'))->rules('required')->thumbnail('small', $width = 200)->removable()->uniqueName();
        $form->switch('off', __('Выкл'))->options(['1' => 'Выкл', '0'=> 'Вкл'])->default('0');

        return $form;
    }
}
