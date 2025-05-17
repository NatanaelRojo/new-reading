<?php

namespace App\Filament\Resources\GenreResource\Forms;

use App\Filament\Resources\Abstract\AbstractCreateForm;
use Filament\Forms\Components\TextInput;

class CreateForm extends AbstractCreateForm
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
