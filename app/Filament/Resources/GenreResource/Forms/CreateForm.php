<?php

namespace App\Filament\Resources\GenreResource\Forms;

use Filament\Forms\Components\TextInput;

class CreateForm
{
    public static function getFields(): array
    {
        return [
            TextInput::make('name')
                ->required(),
        ];
    }

    public static function make(): array
    {
        return [
            TextInput::make('name')
                ->required(),
        ];
    }
}
