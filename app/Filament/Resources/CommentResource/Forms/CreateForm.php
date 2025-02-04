<?php

namespace App\Filament\Resources\CommentResource\Forms;

use App\Filament\Resources\BookResource\Forms\CreateForm as FormsCreateForm;
use App\Filament\Resources\UserResource\Forms\CreateForm as UserResourceFormsCreateForm;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class CreateForm
{
    public static function make(): array
    {
        return [
            Select::make('book_id')
            ->required()
                ->relationship(
                    name: 'book',
                    titleAttribute: 'title'
                )
                ->preload()
                ->searchable()
                ->createOptionForm(FormsCreateForm::getFields()),
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
