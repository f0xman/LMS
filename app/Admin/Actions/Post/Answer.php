<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class Answer extends RowAction
{
    public $name = 'Написать ответ';

    public function handle(Model $model)
    {
        // $model ...

        return $this->response()->success('Success message.')->refresh();
    }

    public function href()
    {
        return $this->getResource()."/create?parent_id=".$this->getKey();
    }

}
