<?php

namespace App\Filament\Resources\AuthorResource\Forms;

use App\Filament\Resources\BookResource\Forms\CreateForm as FormsCreateForm;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

class CreateForm
{
    public static function getFields(): array
    {
        return [
            TextInput::make('first_name')
                ->required(),
            TextInput::make('last_name')
                ->required(),
            TextInput::make('nationality')
                ->required(),
            Textarea::make('biography')
                ->required(),
                FileUpload::make('image')
                ->avatar()
                ->imageEditor(),
        ];
    }
    public static function make(): array
    {
        return [
            TextInput::make('first_name')
                ->required(),
            TextInput::make('last_name')
                ->required(),
            TextInput::make('nationality')
                ->required(),
            Textarea::make('biography')
                ->required(),
                FileUpload::make('image')
                ->avatar()
                ->imageEditor(),
            Select::make('books')
                ->multiple()
                ->relationship(
                    name: 'books',
                    titleAttribute: 'title'
                )->preload()
                ->searchable()
                ->createOptionForm(FormsCreateForm::getFields()),
        ];
    }
}
