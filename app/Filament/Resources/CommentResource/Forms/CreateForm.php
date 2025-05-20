<?php

namespace App\Filament\Resources\CommentResource\Forms;

use App\Filament\Resources\Abstract\AbstractCreateForm;
use App\Filament\Resources\BookResource\Forms\CreateForm as FormsCreateForm;
use App\Filament\Resources\UserResource\Forms\CreateForm as UserResourceFormsCreateForm;
use App\Models\API\V1\Book;
use App\Models\API\V1\Post;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\MorphToSelect\Type;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class CreateForm extends AbstractCreateForm
{
    public static function make(): array
    {
        return [
            MorphToSelect::make('commentable')
                ->types(self::getMorphTypes()),
                Select::make('user_id')
                ->required()
                ->relationship(
                    name: 'user',
                    titleAttribute: 'name'
                )->preload()
                ->searchable()
                ->createOptionForm(UserResourceFormsCreateForm::make()),
            TextInput::make('body')
                ->required(),
        ];
    }

    private static function getMorphTypes(): array
    {
        return [
            Type::make(Book::class)
            ->titleAttribute('title'),
            Type::make(Post::class)
            ->titleAttribute('body'),
        ];
    }
}
