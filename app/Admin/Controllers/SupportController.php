<?php

namespace App\Admin\Controllers;

use App\Models\Support;
use App\Models\SupportMessage;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;


class SupportController extends AdminController
{ 
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Поддержка';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Support());

        $grid->disableCreateButton();
        $grid->disableFilter();
        $grid->disableRowSelector();
        $grid->disableColumnSelector();
        $grid->disablePagination();

        $grid->actions(function ($actions) {
            $actions->disableDelete();
            //$actions->disableEdit();
            $actions->disableView();
        });

        $grid->column('id', __('#'))->width(50);
        $grid->column('title', __('Тема'));
        $grid->column('users.name', __('Пользователь'));
        $grid->column('users.email', __('Почта'));
        $grid->column('created_at', __('Создано'));


        return $grid;
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Support());

        $form->disableReset();
        $form->disableViewCheck();
        $form->disableEditingCheck();
        $form->disableCreatingCheck();

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
        });

        $form->text('title')->readonly();
        $form->text('created_at', __('Дата обращения'))->readonly();


        $form->hasMany('supportnestedmessages', 'Добавить сообщение', function (Form\NestedForm $form) {

            $form->textarea('message', __('Сообщение'));
            $form->hidden('admin')->value('1');
            $form->hidden('unread')->value('1');
            $form->hidden('user_id')->value('1');

        })->disableDelete();

        //Ignore fields to store
        $form->ignore('title', 'user_id');

        return $form;
    }

     /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->title('Поддержка')
            ->description('тикеты')
            ->row($this->form()->edit($id))
            ->row(Admin::grid(SupportMessage::class, function (Grid $grid) use ($id) {

                $grid->model()->orderBy('id', 'desc');

                $grid->disableCreateButton();
                $grid->disableFilter();
                $grid->disableRowSelector();
                $grid->disableColumnSelector();
                $grid->disableActions();
                $grid->disablePagination();

                $grid->setName('messages')
                    ->setTitle('Сообщения')
                    ->setRelation(Support::find($id)->supportmessages())
                    ->resource('/support-messages');

                 $grid->rows(function ($row) {

                    if ( $row->admin == '1' ) {
                        $row->style("background-color:#F5F5F5; font-weight:bold");
                    }
                 });

                $grid->id('#');
                $grid->message('Сообщение');
                $grid->column('created_at');

            }));
    }

}
