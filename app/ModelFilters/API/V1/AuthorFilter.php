<?php

namespace App\ModelFilters\API\V1;

use EloquentFilter\ModelFilter;
use Illuminate\Database\Eloquent\Builder;

class AuthorFilter extends ModelFilter
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
        return $this->where(function (Builder $builder) use ($name) {
            $builder->where('name', 'like', "%$name%")
                ->orWhere('last_name', 'like', "%$name%");
        });
    }
}
