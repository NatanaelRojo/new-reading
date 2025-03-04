<?php

namespace App\Filament\Resources\PostResource\Forms;

use App\Filament\Resources\BookResource\Forms\CreateForm as BookResourceFormsCreateForm;
use App\Filament\Resources\UserResource\Forms\CreateForm as FormsCreateForm;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class CreateForm
{
    public static function make(): array
    {
        return [
            Select::make('user_id')
                ->required()
                ->relationship(
                    name: 'user',
                    titleAttribute: 'name'
                )->preload()
                ->searchable()
                ->createOptionForm(FormsCreateForm::make()),
            Select::make('book_id')
                ->required()
                ->relationship(
                    name: 'book',
                    titleAttribute: 'title'
                )->preload()
                ->searchable()
                ->createOptionForm(BookResourceFormsCreateForm::make()),
            Textarea::make('body')
                ->required(),
            TextInput::make('progress')
                ->required()
                ->numeric()
                ->minLength(0)
                ->maxLength(100),
        ];
    }
}
