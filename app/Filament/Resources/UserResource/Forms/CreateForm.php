<?php

namespace App\Filament\Resources\UserResource\Forms;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class CreateForm
{
    public static function make(): array
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
}
