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
    ];

    /**
     * Filter by tag name.
     *
     * @param string $name
     * @return BookFilter
     */
    public function tag(string $name): self
    {
        return $this->related('users', function ($query) use ($name) {
            $query->firstWhere('user_id', auth()->id())
            ->pivot
            ->tag()->firstWhere('name', $name);
        });
    }

    /**
     * Filter by title.
     *
     * @param string $title
     * @return BookFilter
     */
    public function title(string $title): self
    {
        return $this->where('title', 'like', "%$title%");
    }
}
