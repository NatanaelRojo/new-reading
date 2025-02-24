<?php

namespace App\ModelFilters\API\V1;

use EloquentFilter\ModelFilter;

class TagFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function name(string $name): self
    {
        return $this->where('name', 'like', "%$name%");
    }
}
