<?php

namespace App\Admin\Controllers;

use App\Models\Comment;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use App\Admin\Actions\Post\Answer;
use Illuminate\Support\MessageBag;

class CommentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Комментарии к урокам';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Comment());

        $grid->disableCreateButton();
        $grid->disableFilter();
        $grid->disableRowSelector();
        $grid->disableColumnSelector();
        $grid->disablePagination();
        $grid->disableExport();

        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableEdit();
            $actions->disableView();
            $actions->add(new Answer);
        });

        $grid->column('id', __('#'));
        $grid->column('lesson.title', __('Урок'));
        $grid->column('user.name', __('Студент'));        
        $grid->column('comment', __('Комментарий'));

        return $grid;
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Comment());

        $form->tools(function (Form\Tools $tools) {
            //$tools->disableList();
            //$tools->disableDelete();
            $tools->disableView();
        });

        $form->disableReset();

        $form->disableViewCheck();
        $form->disableEditingCheck();
        $form->disableCreatingCheck();

        if(request()->parent_id) {

            $parent = Comment::find(request()->parent_id);

            $form->html($parent->comment);
            $form->divider();
            $form->textarea('comment', __('Ответ на комментарий'))->rules('required');

            $form->hidden('parent_id')->value(request()->parent_id);
            $form->hidden('lesson_id')->value($parent->lesson_id);
            $form->hidden('user_id')->value(1);

        } else {
            $form->disableSubmit();
            $form->textarea('comment', __('Комментарий'))->readonly();
        }

        // callback after save
        $form->saved(function (Form $form) {

            $success = new MessageBag([
                'title'   => 'Успешно',
                'message' => 'Добавлен....',
            ]);

            return redirect('/admin/comments')->with(compact('success'));

        });

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
            ->title('header')
            ->description('description')
            ->row($this->form()->edit($id))
            ->row(Admin::grid(Comment::class, function (Grid $grid) use ($id) {

                $grid->disableFilter();
                $grid->disableRowSelector();
                $grid->disableColumnSelector();
                $grid->disableActions();
                $grid->disablePagination();
                $grid->disableExport();


                $grid->setName('comments')
                    ->setTitle('Ответ администратора')
                    ->setRelation(Comment::find($id)->comments())
                    ->resource('/admin/comments');

                $grid->id('#');
                $grid->comment('Комментарий');
                $grid->created_at('Создан');

            }));
    }



}
