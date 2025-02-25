<?php

namespace App\ModelFilters\API\V1;

use EloquentFilter\ModelFilter;

class BookFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [
        'authors' => [
            'author_name' => 'authorName',
        ],
        'genres' => [
            'genre_name' => 'name',
        ],
        'tags' => [
            'tag_name' => 'name',
        ],
    ];

    public function title(string $title): self
    {
        return $this->where('title', 'like', "%$title%");
    }
}
