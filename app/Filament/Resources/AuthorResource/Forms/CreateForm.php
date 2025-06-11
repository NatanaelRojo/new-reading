<?php

namespace App\Filament\Resources\AuthorResource\Forms;

use App\Filament\Resources\Abstract\AbstractCreateForm;
use App\Filament\Resources\BookResource\Forms\CreateForm as FormsCreateForm;
use App\Filament\Resources\UserResource\Forms\CreateForm as UserResourceFormsCreateForm;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

class CreateForm extends AbstractCreateForm
{
    public static function make(): array
    {
        return [
            Tabs::make('Tabs')
                ->tabs(static::getTabs()),
        ];
    }

    private static function getTabs(): array
    {
        return [
            Tab::make('Author Details')
                ->schema(static::getFields()),
            Tab::make('User details')
                ->schema(static::getUserFields()),
        ];
    }

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

    private static function getUserFields(): array
    {
        return  [
            TextInput::make('user.first_name')
                ->required(),
            TextInput::make('user.last_name')
                ->required(),
                TextInput::make('user.email')
                ->required()
                ->email(),
            TextInput::make('user.name')
                ->required(),
            TextInput::make('user.description')
                ->required(),
            TextInput::make('user.password')
                ->required(),
            DatePicker::make('user.birth_date')
                ->required(),
    //             Select::make('user.roles')
    // ->multiple()
    // ->relationship('roles', 'name')
    // ->preload(),
        ];
    }
}
