<?php

namespace App\Filament\Resources\BookResource\Forms;

use App\Filament\Resources\AuthorResource\Forms\CreateForm as FormsCreateForm;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CreateForm
{
    public static function getFields(): array
    {
        return [
            TextInput::make('title')
                ->required(),
            Textarea::make('synopsis')
                ->required(),
            TextInput::make('isbn')
                ->required(),
            TextInput::make('pages_amount')
                ->required()
                ->numeric()
                ->minValue(1),
            TextInput::make('chapters_amount')
            ->required()
            ->numeric()
            ->minValue(1),
            FileUpload::make('image_url')
            ->imageEditor(),
        ];
    }

    public static function make(): array
    {
        return [
            TextInput::make('title')
                ->required(),
            Textarea::make('synopsis')
                ->required(),
            TextInput::make('isbn')
                ->required(),
            TextInput::make('pages_amount')
                ->required()
                ->numeric()
                ->minValue(1),
            TextInput::make('chapters_amount')
            ->required()
            ->numeric()
            ->minValue(1),
            FileUpload::make('image_url')
            ->imageEditor(),
            Select::make('authors')
            ->multiple()
            ->relationship(
                name: 'authors',
                titleAttribute: 'full_name'
            )->preload()
            ->searchable()
            ->createOptionForm(FormsCreateForm::getFields()),
        ];
    }
}
