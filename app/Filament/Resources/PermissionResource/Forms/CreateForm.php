<?php

namespace App\Filament\Resources\PermissionResource\Forms;

use App\Filament\Resources\Abstract\AbstractCreateForm;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class CreateForm extends AbstractCreateForm
{
    public static function make(): array
    {
        return [
                            TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->readOnly(fn (string $operation): bool => $operation === 'edit'),
                 Select::make('guard_name')
                    ->options(['web' => 'Web', 'api' => 'API'])
                    ->required()
                    ->default('web'),
        ];
    }
}
