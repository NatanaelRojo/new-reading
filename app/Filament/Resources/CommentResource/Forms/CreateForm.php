<?php

namespace App\Filament\Resources\CommentResource\Forms;

use App\Filament\Resources\BookResource\Forms\CreateForm as FormsCreateForm;
use App\Filament\Resources\UserResource\Forms\CreateForm as UserResourceFormsCreateForm;
use App\Models\API\V1\Book;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\MorphToSelect\Type;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class CreateForm
{
    public static function make(): array
    {
        return [
            MorphToSelect::make('commentable')
                ->types([
                    Type::make(Book::class)
                        ->titleAttribute('title'),
                ])->preload()
                ->searchable(),
            // Select::make('book_id')
            // ->required()
            //     ->relationship(
            //         name: 'book',
            //         titleAttribute: 'title'
            //     )
            //     ->preload()
            //     ->searchable(),
            //     // ->createOptionForm(FormsCreateForm::getFields()),
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
}
