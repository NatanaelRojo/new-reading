<?php

namespace App\Filament\Resources\TagResource\Forms;

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

    /**
     * Make a form.
     * @return array
     */
    public static function make(): array
    {
        return [
            TextInput::make('name')
                ->required(),
        ];
    }
}
