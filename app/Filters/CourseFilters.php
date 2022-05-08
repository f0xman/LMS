<?php

namespace App\Filters;

class CourseFilters extends QueryFilter
{
    public function category($id)
    {
        return $this->builder->where('category_id', $id);
    }

    public function direction($directionId)
    {
        return $this->builder->where('direction_id', $directionId);
    }

}
