<?php

namespace App\Filament\Resources\BookResource\Forms;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CreateForm
{
    public static function make(): array
    {
        return [
            TextInput::make('title')
                ->required(),
            TextInput::make('synopsis')
                ->required(),
            TextInput::make('isbn')
                ->required(),
            TextInput::make('pages_amount')
                ->required()
                ->numeric()
                ->integer()
                ->minValue(1),
            TextInput::make('chapters_amount')
                ->required()
                ->numeric()
                ->integer()
                ->minValue(1),
                FileUpload::make('image_url')
                ->imageEditor(),
                Select::make('books')
                ->multiple()
                ->relationship(
                    name: 'authors',
                    titleAttribute: 'full_name'
                )
                ->preload()
                ->searchable(),
        ];
    }
}
