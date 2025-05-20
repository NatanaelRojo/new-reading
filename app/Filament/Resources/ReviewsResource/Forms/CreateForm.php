<?php

namespace App\Filament\Resources\ReviewsResource\Forms;

use App\Filament\Resources\Abstract\AbstractCreateForm;
use App\Filament\Resources\BookResource\Forms\CreateForm as FormsCreateForm;
use Filament\Forms;
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
    public static function make(): array
    {
        return [
            TextInput::make('rating')
                ->required()
                ->numeric()
                ->integer()
                ->minValue(1)
                ->maxValue(5),
            TextInput::make('like_count')
                ->required()
                ->numeric()
                ->integer()
                ->minValue(0),
            TextInput::make('dislike_count')
                ->required()
                ->numeric()
                ->integer()
                ->minValue(0),
            Textarea::make('comment'),
            Select::make('book_id')
                ->required()
                ->relationship(name: 'book', titleAttribute: 'title')
                ->preload()
                ->searchable()
                ->createOptionForm(FormsCreateForm::getFields()),
            Select::make('user_id')
                ->required()
                ->relationship(name: 'user', titleAttribute: 'name')
                ->preload()
                ->searchable(),
        ];
    }
}
