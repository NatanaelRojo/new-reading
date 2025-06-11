<?php

namespace App\Filament\Resources\UserResource\Forms;

use App\Filament\Resources\Abstract\AbstractCreateForm;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class CreateForm extends AbstractCreateForm
{
    public static function make(): array
    {
        return [
            Tabs::make('Tabs')
                ->tabs(static::getTabs())
                    ->activeTab(1),
        ];
    }

    private static function getTabs(): array
    {
        return [
            Tab::make('User Details')
                ->schema(static::getFields()),
            Tab::make('Author details')
                ->schema(static::getAuthorFields()),
        ];
    }

    private static function getFields(): array
    {
        return [
            TextInput::make('first_name')
                ->required(),
            TextInput::make('last_name')
                ->required(),
                TextInput::make('email')
                ->required()
                ->email(),
            TextInput::make('name')
                ->required(),
            TextInput::make('description')
                ->required(),
            TextInput::make('password')
                ->required(),
            DatePicker::make('birth_date')
                ->required(),
                Select::make('roles')
    ->multiple()
    ->relationship('roles', 'name')
    ->preload(),
        ];
    }

    public static function getAuthorFields(): array
    {
        return [
            TextInput::make('author.first_name')
                ->required(),
            TextInput::make('author.last_name')
                ->required(),
            TextInput::make('author.nationality')
                ->required(),
            Textarea::make('author.biography')
                ->required(),
                FileUpload::make('author.image')
                ->avatar()
                ->imageEditor(),
        ];
    }
}
