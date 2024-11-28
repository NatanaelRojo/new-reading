<?php

namespace App\Filament\Resources\AuthorResource\Forms;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

class CreateForm
{
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
                )
                ->preload()
                ->searchable(),
        ];
    }
}
