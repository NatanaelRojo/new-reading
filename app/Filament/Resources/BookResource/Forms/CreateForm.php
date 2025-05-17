<?php

namespace App\Filament\Resources\BookResource\Forms;

use App\Filament\Resources\Abstract\AbstractCreateForm;
use App\Filament\Resources\AuthorResource\Forms\CreateForm as FormsCreateForm;
use App\Filament\Resources\TagResource\Forms\CreateForm as TagResourceFormsCreateForm;
use App\Filament\Resources\UserResource\Forms\CreateForm as UserResourceFormsCreateForm;
use App\Models\API\V1\Tag;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
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

class CreateForm extends AbstractCreateForm
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
            DatePicker::make('published_at')
                ->required()
                ->minDate(now()->subYears(150))
                ->maxDate(now()),
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
            DatePicker::make('published_at')
                ->required()
                ->minDate(now()->subYears(150))
                ->maxDate(now()),
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
                Select::make('users')
                ->relationship(
                    name: 'users',
                    titleAttribute: 'name'
                )->preload()
                ->searchable()
                ->createOptionForm(UserResourceFormsCreateForm::make()),
        ];
    }
}
