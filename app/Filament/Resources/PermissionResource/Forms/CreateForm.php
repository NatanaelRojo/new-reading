<?php

namespace App\Filament\Resources\PermissionResource\Forms;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class CreateForm
{
    public static function make(): array
    {
        return [
                            TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true), // Ensure name is unique
                 Select::make('guard_name')
                    ->options(['web' => 'Web', 'api' => 'API'])
                    ->required()
                    ->default('web'),
        ];
    }
}
